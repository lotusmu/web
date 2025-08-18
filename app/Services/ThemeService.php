<?php

namespace App\Services;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use InvalidArgumentException;

class ThemeService
{
    public function getActiveTheme(): string
    {
        return Cache::remember('active_theme', 3600, function () {
            try {
                return DB::table('app_settings')
                    ->where('key', 'active_theme')
                    ->value('value') ?? 'default';
            } catch (\Exception $e) {
                // Fallback to default theme if database is not available
                // This can happen during migrations or deployment
                return 'default';
            }
        });
    }

    public function setTheme(string $theme): void
    {
        // Validate theme exists
        if (! $this->themeExists($theme)) {
            throw new InvalidArgumentException("Theme '{$theme}' does not exist.");
        }

        try {
            DB::table('app_settings')->updateOrInsert(
                ['key' => 'active_theme'],
                ['value' => $theme, 'updated_at' => now()]
            );

            Cache::forget('active_theme');
            Artisan::call('view:clear');
        } catch (\Exception $e) {
            throw new \RuntimeException('Unable to save theme setting: '.$e->getMessage());
        }
    }

    public function getAvailableThemes(): array
    {
        $themesPath = resource_path('views/themes');

        if (! File::exists($themesPath)) {
            // Return empty array if themes directory doesn't exist yet
            return [];
        }

        $themes = collect(File::directories($themesPath))
            ->mapWithKeys(fn ($path) => [
                basename($path) => Str::title(str_replace('-', ' ', basename($path))),
            ])->toArray();

        // Always include default if not found
        if (! isset($themes['default'])) {
            $themes = ['default' => 'Default'] + $themes;
        }

        return $themes;
    }

    public function themeExists(string $theme): bool
    {
        return File::exists(resource_path("views/themes/{$theme}"));
    }

    public function getThemeViewPath(string $theme, string $viewName): string
    {
        return "themes.{$theme}.{$viewName}";
    }
}
