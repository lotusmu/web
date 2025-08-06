<?php

namespace App\Providers;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;
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

        $this->registerBladeDirectives();
    }

    /**
     * Register custom Blade directives
     */
    private function registerBladeDirectives(): void
    {
        // Register theme-aware component directive
        Blade::directive('themeComponent', function ($expression) {
            // Parse the expression to separate component name and data
            $parts = explode(',', $expression, 2);
            $componentName = trim($parts[0], " '\"");
            $data = isset($parts[1]) ? trim($parts[1]) : '[]';

            // Convert dot notation to directory structure (article.preview -> article/preview)
            $componentPath = str_replace('.', '/', $componentName);

            return "<?php echo \$__env->make('themes.'.config('app.theme', 'default').'.components.{$componentPath}', array_merge(\Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']), {$data}))->render(); ?>";
        });
    }
}
