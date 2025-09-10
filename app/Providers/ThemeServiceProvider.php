<?php

namespace App\Providers;

use App\Services\ThemeAssetService;
use App\Services\ThemeService;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class ThemeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ThemeService::class);
        $this->app->singleton(ThemeAssetService::class);
    }

    public function boot(): void
    {
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

            // Use ThemeService to get active theme instead of config
            return "<?php
                if (\Illuminate\Support\Facades\Schema::hasTable('app_settings')) {
                    \$activeTheme = app(\App\Services\ThemeService::class)->getActiveTheme();
                    \$themePath = 'themes.'.\$activeTheme.'.components.{$componentPath}';
                    \$defaultPath = 'themes.default.components.{$componentPath}';

                    if (view()->exists(\$themePath)) {
                        echo \$__env->make(\$themePath, array_merge(\Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']), {$data}))->render();
                    } elseif (view()->exists(\$defaultPath)) {
                        echo \$__env->make(\$defaultPath, array_merge(\Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']), {$data}))->render();
                    } else {
                        throw new \Exception('Theme component not found: {$componentPath}');
                    }
                } else {
                    // Fallback to default theme component during migrations
                    \$defaultPath = 'themes.default.components.{$componentPath}';
                    if (view()->exists(\$defaultPath)) {
                        echo \$__env->make(\$defaultPath, array_merge(\Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']), {$data}))->render();
                    } else {
                        throw new \Exception('Theme component not found: {$componentPath}');
                    }
                }
            ?>";
        });

        // Register Blade directive for theme assets - Fixed version
        Blade::directive('themeAssets', function () {
            return "<?php
                if (\Illuminate\Support\Facades\Schema::hasTable('app_settings')) {
                    \$themeAssetService = app(\App\Services\ThemeAssetService::class);
                    \$assets = \$themeAssetService->getThemeAssets();
                    \$viteAssets = array_filter(\$assets);
                    if (!empty(\$viteAssets)) {
                        echo app('Illuminate\\\Foundation\\\Vite')(\$viteAssets);
                    }
                }
            ?>";
        });
    }
}
