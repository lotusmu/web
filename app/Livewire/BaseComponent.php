<?php

namespace App\Livewire;

use App\Services\ThemeService;
use Exception;
use Livewire\Component;

abstract class BaseComponent extends Component
{
    protected ThemeService $themeService;

    public function boot(): void
    {
        $this->themeService = app(ThemeService::class);
    }

    public function render()
    {
        $theme = $this->themeService->getActiveTheme();
        $viewName = $this->getViewName();

        // Try theme-specific view first
        $themeViewPath = "themes.{$theme}.{$viewName}";
        if (view()->exists($themeViewPath)) {
            return view($themeViewPath)->layout($this->getLayout());
        }

        // Fallback to default theme
        $defaultViewPath = "themes.default.{$viewName}";
        if (view()->exists($defaultViewPath)) {
            return view($defaultViewPath)->layout($this->getLayout());
        }

        // Final fallback - throw descriptive error
        throw new Exception("View not found: {$viewName} in any theme");
    }

    protected function getLayout(): string
    {
        $theme = $this->themeService->getActiveTheme();
        $layoutType = $this->getLayoutType(); // 'app' or 'guest'

        // Try theme-specific layout first
        if (view()->exists("themes.{$theme}.layouts.{$layoutType}")) {
            return "themes.{$theme}.layouts.{$layoutType}";
        }

        // Fallback to default theme layout
        return "themes.default.layouts.{$layoutType}";
    }

    protected function getLayoutType(): string
    {
        // Override this in components that need guest layout
        return 'app';
    }

    abstract protected function getViewName(): string;
}
