<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

class ThemeAssetService
{
    private ThemeService $themeService;

    private array $assetCache = [];

    public function __construct(ThemeService $themeService)
    {
        $this->themeService = $themeService;
    }

    /**
     * Get Vite assets for the currently active theme
     */
    public function getThemeAssets(): array
    {
        $activeTheme = $this->themeService->getActiveTheme();

        if (isset($this->assetCache[$activeTheme])) {
            return $this->assetCache[$activeTheme];
        }

        $assets = [
            'css' => $this->getThemeCssPath($activeTheme),
            'js' => $this->getThemeJsPath($activeTheme),
        ];

        $this->assetCache[$activeTheme] = $assets;

        return $assets;
    }

    /**
     * Get CSS path for theme (with fallback to default)
     */
    private function getThemeCssPath(string $theme): ?string
    {
        $cssPath = "resources/themes/{$theme}/css/theme.css";

        if (File::exists(base_path($cssPath))) {
            return $cssPath;
        }

        // Fallback to default theme
        $defaultCssPath = 'resources/themes/default/css/theme.css';
        if (File::exists(base_path($defaultCssPath))) {
            return $defaultCssPath;
        }

        return null;
    }

    /**
     * Get JS path for theme (with fallback to default)
     */
    private function getThemeJsPath(string $theme): ?string
    {
        $jsPath = "resources/themes/{$theme}/js/theme.js";

        if (File::exists(base_path($jsPath))) {
            return $jsPath;
        }

        // Fallback to default theme
        $defaultJsPath = 'resources/themes/default/js/theme.js';
        if (File::exists(base_path($defaultJsPath))) {
            return $defaultJsPath;
        }

        return null;
    }

    /**
     * Generate Vite directive for theme assets
     */
    public function getViteDirective(): string
    {
        $assets = $this->getThemeAssets();
        $viteAssets = array_filter($assets);

        if (empty($viteAssets)) {
            return '';
        }

        $assetList = "'".implode("', '", $viteAssets)."'";

        return "@vite([{$assetList}])";
    }

    /**
     * Get theme-specific image asset
     */
    public function getThemeImage(string $imagePath): string
    {
        $activeTheme = $this->themeService->getActiveTheme();
        $themedImagePath = "images/themes/{$activeTheme}/{$imagePath}";

        if (File::exists(public_path($themedImagePath))) {
            return asset($themedImagePath);
        }

        // Fallback to default theme
        $defaultImagePath = "images/themes/default/{$imagePath}";
        if (File::exists(public_path($defaultImagePath))) {
            return asset($defaultImagePath);
        }

        // Final fallback to regular images directory
        return asset("images/{$imagePath}");
    }

    /**
     * Get theme-specific logo with readable variant names
     */
    public function getThemeLogo(string $variant = 'light'): string
    {
        $activeTheme = $this->themeService->getActiveTheme();

        // Map variants to your file naming convention
        $logoFileName = match ($variant) {
            'light', 'default' => 'logotype.svg',
            'dark', 'white' => 'logotype-white.svg',
            'vertical' => 'logotype-vertical.svg',
            'vertical-dark' => 'logotype-vertical-white.svg',
            'mark' => 'mark.svg',
            'mark-dark' => 'mark-dark.svg',
            'wordmark' => 'wordmark.svg',
            'wordmark-dark' => 'wordmark-white.svg',
            default => 'logotype.svg'
        };

        // Try theme-specific logo
        $themeLogoPath = "images/themes/{$activeTheme}/brand/{$logoFileName}";
        if (File::exists(public_path($themeLogoPath))) {
            return asset($themeLogoPath);
        }

        // Fallback to default theme logo
        $defaultLogoPath = "images/themes/default/brand/{$logoFileName}";
        if (File::exists(public_path($defaultLogoPath))) {
            return asset($defaultLogoPath);
        }

        // Final fallback to your existing brand directory
        $fallbackLogoPath = "images/brand/{$logoFileName}";

        return asset($fallbackLogoPath);
    }

    /**
     * Get theme-specific favicon with variant support
     */
    public function getThemeFavicon(string $variant = 'light', string $fileName = 'favicon.ico'): string
    {
        $activeTheme = $this->themeService->getActiveTheme();

        // For themes with light/dark variants (like yulan)
        $variantFaviconPath = "images/themes/{$activeTheme}/favicons/{$variant}/{$fileName}";
        if (File::exists(public_path($variantFaviconPath))) {
            return asset($variantFaviconPath);
        }

        // For themes without variants (like default) - try direct path
        $directFaviconPath = "images/themes/{$activeTheme}/favicons/{$fileName}";
        if (File::exists(public_path($directFaviconPath))) {
            return asset($directFaviconPath);
        }

        // Fallback to default theme with variant
        $defaultVariantPath = "images/themes/default/favicons/{$variant}/{$fileName}";
        if (File::exists(public_path($defaultVariantPath))) {
            return asset($defaultVariantPath);
        }

        // Fallback to default theme direct path
        $defaultDirectPath = "images/themes/default/favicons/{$fileName}";
        if (File::exists(public_path($defaultDirectPath))) {
            return asset($defaultDirectPath);
        }

        // Final fallback to root public directory
        return asset($fileName);
    }

    /**
     * Get all theme asset paths for debugging
     */
    public function getAssetPaths(): array
    {
        return [
            'active_theme' => $this->themeService->getActiveTheme(),
            'assets' => $this->getThemeAssets(),
            'vite_directive' => $this->getViteDirective(),
        ];
    }
}
