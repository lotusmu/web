<?php

namespace App\Providers;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->isProduction() || Model::shouldBeStrict();

        Vite::prefetch(3);

        Gate::define('viewPulse', function (User $user) {
            return $user->is_admin;
        });
    }
}
