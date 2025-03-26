<?php

namespace App\Models\Utility;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'group',
        'settings',
    ];

    protected $casts = [
        'settings' => 'array',
    ];

    // Store loaded settings in a static property for the current request
    protected static array $loadedSettings = [];

    protected static function booted(): void
    {
        static::saved(function ($setting) {
            Cache::forget("settings.{$setting->group}");
            // Also clear the request-level cache
            unset(static::$loadedSettings[$setting->group]);
        });

        static::deleted(function ($setting) {
            Cache::forget("settings.{$setting->group}");
            // Also clear the request-level cache
            unset(static::$loadedSettings[$setting->group]);
        });
    }

    public static function getGroup(string $group): array
    {
        // First check the request-level cache
        if (isset(static::$loadedSettings[$group])) {
            return static::$loadedSettings[$group];
        }

        // Then check the Laravel cache
        static::$loadedSettings[$group] = Cache::rememberForever("settings.{$group}", function () use ($group) {
            $settings = static::where('group', $group)->first();

            return $settings?->settings ?? [];
        });

        return static::$loadedSettings[$group];
    }

    public static function getValue(string $group, string $key, mixed $default = null): mixed
    {
        $settings = static::getGroup($group);

        return data_get($settings, $key, $default);
    }
}
