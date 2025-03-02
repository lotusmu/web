<?php

namespace App\Actions\Banners;

use App\Enums\Utility\UpdateBannerType;
use App\Models\Utility\GameServer;
use App\Models\Utility\UpdateBanner;

class RetrieveBanner
{
    /**
     * Get an announcement banner
     */
    public function getAnnouncement(): ?UpdateBanner
    {
        return $this->getBannerByType(UpdateBannerType::ANNOUNCEMENT);
    }

    /**
     * Get non-announcement banner (update banner)
     */
    public function getUpdate(): ?UpdateBanner
    {
        return $this->getFromCache('active_updates_banner', function () {
            return UpdateBanner::where('is_active', true)
                ->where('type', '!=', UpdateBannerType::ANNOUNCEMENT->value)
                ->first();
        }, now()->addDay());
    }

    /**
     * Get a banner by specific type
     */
    public function getBannerByType(UpdateBannerType $type): ?UpdateBanner
    {
        $cacheKey = "banner_type_{$type->value}";

        return $this->getFromCache($cacheKey, function () use ($type) {
            return UpdateBanner::where('is_active', true)
                ->where('type', $type)
                ->first();
        }, now()->addDay());
    }

    /**
     * Get upcoming launch server
     */
    public function getLaunchServer(): ?GameServer
    {
        return $this->getFromCache('launch_server', function () {
            return GameServer::where('is_active', true)
                ->whereNotNull('launch_date')
                ->where('launch_date', '>', now())
                ->orderBy('launch_date')
                ->first();
        }, now()->addHour());
    }

    /**
     * Determine if we should show a launch server banner
     */
    public function shouldShowLaunchServer(?GameServer $server): bool
    {
        if (! $server || ! $server->launch_date) {
            return false;
        }

        // Show for pre-launch countdown and 7 days post-launch
        if ($server->launch_date->isPast()) {
            return $server->launch_date->diffInDays(now()) <= 7;
        }

        return true;
    }

    /**
     * Get data from cache with callback for cache miss
     */
    private function getFromCache(string $key, callable $callback, $ttl = null): mixed
    {
        if (cache()->has($key)) {
            return cache()->get($key);
        }

        $result = $callback();

        if ($result) {
            cache()->put($key, $result, $ttl ?? now()->addDay());
        }

        return $result;
    }
}
