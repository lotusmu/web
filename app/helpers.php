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
     * Get theme-specific logo
     */
    function theme_logo(bool $isDark = false): string
    {
        return app(ThemeAssetService::class)->getThemeLogo($isDark);
    }
}
