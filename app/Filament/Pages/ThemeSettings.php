<?php

namespace App\Filament\Pages;

use App\Services\ThemeService;
use Exception;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\HtmlString;

class ThemeSettings extends Page
{
    protected static ?string $navigationGroup = 'Settings';

    protected static string $view = 'filament.pages.theme-settings';

    public ?string $selectedTheme = null;

    public function mount(): void
    {
        $this->selectedTheme = app(ThemeService::class)->getActiveTheme();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Theme Selection')
                    ->description('Select a theme and preview it before applying')
                    ->aside()
                    ->schema([
                        Radio::make('selectedTheme')
                            ->label('Available Themes')
                            ->options($this->getThemeOptions())
                            ->descriptions($this->getThemeDescriptions())
                            ->reactive(),

                        Placeholder::make('preview')
                            ->label('Theme Preview')
                            ->content(function (callable $get) {
                                $selectedTheme = $get('selectedTheme') ?? app(ThemeService::class)->getActiveTheme();
                                $currentTheme = app(ThemeService::class)->getActiveTheme();

                                $previewPath = "images/theme-previews/{$selectedTheme}.png";

                                $content = '<div class="space-y-4">';

                                // Show preview image if available
                                if (file_exists(public_path($previewPath))) {
                                    $content .= '<div>';
                                    $content .= '<img src="'.asset($previewPath).'" class="w-full max-w-lg mx-auto rounded-lg shadow-sm" alt="'.$selectedTheme.' preview" />';
                                    $content .= '</div>';
                                } else {
                                    $content .= '<div>';
                                    $content .= '<p>No preview available for '.$selectedTheme.'</p>';
                                    $content .= '<p class="opacity-50">Add '.$selectedTheme.'.png to public/images/theme-previews/</p>';
                                    $content .= '</div>';
                                }

                                $content .= '</div>';

                                return new HtmlString($content);
                            })
                            ->reactive(),

                        // Apply Button - only show when different theme selected
                        Actions::make([
                            Action::make('applyTheme')
                                ->label('Apply Theme')
                                ->color('primary')
                                ->action(fn () => $this->applyTheme())
                                ->disabled(function (callable $get) {
                                    $selectedTheme = $get('selectedTheme') ?? app(ThemeService::class)->getActiveTheme();
                                    $currentTheme = app(ThemeService::class)->getActiveTheme();

                                    return $selectedTheme === $currentTheme;
                                }),
                        ]),
                    ])
                    ->columns(2),
            ])
            ->statePath('');
    }

    public function applyTheme(): void
    {
        if ($this->selectedTheme && $this->selectedTheme !== app(ThemeService::class)->getActiveTheme()) {
            $this->switchToTheme($this->selectedTheme);
        } else {
            Notification::make()
                ->title('No Change')
                ->body('Selected theme is already active.')
                ->info()
                ->send();
        }
    }

    private function switchToTheme(string $theme): void
    {
        try {
            app(ThemeService::class)->setTheme($theme);

            Notification::make()
                ->title('Theme Applied')
                ->body('Successfully switched to "'.ucfirst($theme).'" theme!')
                ->success()
                ->send();
        } catch (Exception $e) {
            Notification::make()
                ->title('Error')
                ->body('Failed to apply theme: '.$e->getMessage())
                ->danger()
                ->send();
        }
    }

    private function getThemeOptions(): array
    {
        $availableThemes = app(ThemeService::class)->getAvailableThemes();

        $options = [];
        foreach ($availableThemes as $key => $name) {
            $options[$key] = $name;
        }

        return $options;
    }

    private function getThemeDescriptions(): array
    {
        $currentTheme = app(ThemeService::class)->getActiveTheme();

        $descriptions = [
            'default' => 'Lotus Mu Theme',
            'yulan' => 'Yulan Mu Theme',
        ];

        // Add "Currently active" to the active theme description
        foreach ($descriptions as $key => $description) {
            if ($key === $currentTheme) {
                $descriptions[$key] = $description.' • Currently active';
            }
        }

        return $descriptions;
    }
}
