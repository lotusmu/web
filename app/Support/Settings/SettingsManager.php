<?php

namespace App\Support\Settings;

use App\Models\Utility\Setting;
use Illuminate\Support\Collection;

class SettingsManager
{
    private array $cache = [];

    public function get(string $group, string $key, mixed $default = null): mixed
    {
        return Setting::getValue($group, $key, $default);
    }

    public function group(string $group): array
    {
        return Setting::getGroup($group);
    }

    public function flush(string $group): void
    {
        Setting::where('group', $group)->delete();
    }

    /**
     * Get all available setting groups
     */
    public function groups(): Collection
    {
        return Setting::select('group')
            ->distinct()
            ->pluck('group');
    }
}
