<?php

namespace App\Actions\Banners;

use App\Models\Utility\UpdateBanner;
use Exception;
use Illuminate\Support\Facades\Cookie;

class DismissBanner
{
    /**
     * Dismiss a banner and store in a cookie
     */
    public function dismiss(UpdateBanner $banner, string $cookieName = 'announcement_dismissed'): void
    {
        $cookieData = [
            'id' => $banner->id,
            'timestamp' => $banner->updated_at->timestamp,
            'checksum' => md5($banner->content.$banner->url),
        ];

        Cookie::queue($cookieName, json_encode($cookieData), 60 * 24 * 30);
    }

    /**
     * Check if banner has been dismissed
     */
    public function isDismissed(UpdateBanner $banner, string $cookieName = 'announcement_dismissed'): bool
    {
        if (! $banner) {
            return false;
        }

        $cookieValue = request()->cookie($cookieName);

        if (! $cookieValue) {
            return false;
        }

        try {
            $dismissed = json_decode($cookieValue, true);

            if (! is_array($dismissed) ||
                ! isset($dismissed['id']) ||
                ! isset($dismissed['timestamp']) ||
                ! isset($dismissed['checksum'])
            ) {
                return false;
            }

            return $dismissed['id'] === $banner->id
                && $dismissed['timestamp'] === $banner->updated_at->timestamp
                && $dismissed['checksum'] === md5($banner->content.$banner->url);

        } catch (Exception $e) {
            return false;
        }
    }
}
