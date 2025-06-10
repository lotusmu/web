<?php

namespace App\Models\Utility;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'group',
        'server_id',
        'settings',
    ];

    protected $casts = [
        'settings' => 'array',
        'server_id' => 'integer',
    ];

    // Store loaded settings in a static property for the current request
    protected static array $loadedSettings = [];

    protected static function booted(): void
    {
        static::saved(function ($setting) {
            Cache::forget("settings.{$setting->group}.{$setting->server_id}");
            // Also clear the request-level cache
            unset(static::$loadedSettings["{$setting->group}.{$setting->server_id}"]);
        });

        static::deleted(function ($setting) {
            Cache::forget("settings.{$setting->group}.{$setting->server_id}");
            // Also clear the request-level cache
            unset(static::$loadedSettings["{$setting->group}.{$setting->server_id}"]);
        });
    }

    public static function getGroup(string $group, ?int $serverId = null): array
    {
        $serverId = $serverId ?? static::getCurrentServerId();
        $cacheKey = "{$group}.{$serverId}";

        // First check the request-level cache
        if (isset(static::$loadedSettings[$cacheKey])) {
            return static::$loadedSettings[$cacheKey];
        }

        // Then check the Laravel cache
        static::$loadedSettings[$cacheKey] = Cache::rememberForever("settings.{$cacheKey}", function () use ($group, $serverId) {
            $settings = static::where('group', $group)
                ->where('server_id', $serverId)
                ->first();

            return $settings?->settings ?? [];
        });

        return static::$loadedSettings[$cacheKey];
    }

    public static function getValue(string $group, string $key, mixed $default = null, ?int $serverId = null): mixed
    {
        $settings = static::getGroup($group, $serverId);

        return data_get($settings, $key, $default);
    }

    private static function getCurrentServerId(): int
    {
        $connectionName = session('game_db_connection', 'gamedb_main');

        return Cache::remember("server_id_for_{$connectionName}", now()->addHours(1), function () use ($connectionName) {
            return GameServer::where('connection_name', $connectionName)->value('id') ?? 1;
        });
    }

    public function server()
    {
        return $this->belongsTo(GameServer::class);
    }
}
