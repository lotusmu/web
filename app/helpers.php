<?php

use App\Services\ThemeAssetService;

if (! function_exists('theme_asset')) {
    /**
     * Get theme-specific asset path
     */
    function theme_asset(string $path): string
    {
        return app(ThemeAssetService::class)->getThemeImage($path);
    }
}

if (! function_exists('theme_logo')) {
    /**
     * Get theme-specific logo with readable variant names
     */
    function theme_logo(string $variant = 'light'): string
    {
        return app(ThemeAssetService::class)->getThemeLogo($variant);
    }
}

if (! function_exists('theme_favicon')) {
    /**
     * Get theme-specific favicon with variant support
     */
    function theme_favicon(string $variant = 'light', string $fileName = 'favicon.ico'): string
    {
        return app(ThemeAssetService::class)->getThemeFavicon($variant, $fileName);
    }
}
