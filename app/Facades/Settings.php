<?php

namespace App\Facades;

use App\Support\Settings\SettingsManager;
use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed get(string $group, string $key, mixed $default = null)
 * @method static array group(string $group)
 * @method static void flush(string $group)
 */
class Settings extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return SettingsManager::class;
    }
}
