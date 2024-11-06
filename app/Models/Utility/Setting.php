<?php

namespace App\Models\Utility;

use App\Exceptions\Settings\MissingSettingsException;
use App\Support\Settings\SettingsValidator;
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

    protected static function booted(): void
    {
        static::saving(function ($setting) {
            app(SettingsValidator::class)->validate($setting->group, $setting->settings);
        });

        static::saved(function ($setting) {
            Cache::forget("settings.{$setting->group}");
        });

        static::deleted(function ($setting) {
            Cache::forget("settings.{$setting->group}");
        });
    }

    public static function getGroup(string $group): array
    {
        return Cache::rememberForever("settings.{$group}", function () use ($group) {
            $settings = static::where('group', $group)->first();

            if (! $settings) {
                throw new MissingSettingsException("Settings not found for group: {$group}");
            }

            return $settings->settings;
        });
    }

    public static function getValue(string $group, string $key, mixed $default = null): mixed
    {
        try {
            $settings = static::getGroup($group);

            return data_get($settings, $key, $default);
        } catch (MissingSettingsException $e) {
            return $default;
        }
    }
}
