<?php

namespace App\Providers;

use App\Support\Settings\SettingsManager;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SettingsManager::class, function () {
            return new SettingsManager;
        });
    }

    public function boot(): void
    {
        //
    }
}
