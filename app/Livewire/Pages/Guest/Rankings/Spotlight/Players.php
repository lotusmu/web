<?php

namespace App\Livewire\Pages\Guest\Rankings\Spotlight;

use App\Livewire\BaseComponent;
use App\Models\Game\Character;
use App\Models\Game\Ranking\HallOfFame;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Computed;

class Players extends BaseComponent
{
    public const CHARACTER_CONFIG = [
        'dk' => ['class_name' => 'Knights', 'color' => 'red'],
        'dw' => ['class_name' => 'Wizards', 'color' => 'blue'],
        'fe' => ['class_name' => 'Elves', 'color' => 'green'],
        'mg' => ['class_name' => 'Gladiators', 'color' => 'purple'],
        'dl' => ['class_name' => 'Lords', 'color' => 'yellow'],
    ];

    public const DEFAULT_WINNERS = [
        'dk' => 'TBD',
        'dw' => 'TBD',
        'fe' => 'TBD',
        'mg' => 'TBD',
        'dl' => 'TBD',
    ];

    public const COLOR_CLASSES = [
        'red' => [
            'gradient' => 'from-red-700 via-red-600 to-transparent dark:from-red-600 dark:via-red-700',
            'border' => 'border-red-600/90 dark:border-red-400/30',
            'text' => 'text-red-600 dark:text-red-400',
        ],
        'blue' => [
            'gradient' => 'from-blue-700 via-blue-600 to-transparent dark:from-blue-600 dark:via-blue-700',
            'border' => 'border-blue-600/90 dark:border-blue-400/30',
            'text' => 'text-blue-600 dark:text-blue-400',
        ],
        'green' => [
            'gradient' => 'from-green-700 via-green-600 to-transparent dark:from-green-600 dark:via-green-700',
            'border' => 'border-green-600/90 dark:border-green-400/30',
            'text' => 'text-green-600 dark:text-green-400',
        ],
        'purple' => [
            'gradient' => 'from-purple-700 via-purple-600 to-transparent dark:from-purple-600 dark:via-purple-700',
            'border' => 'border-purple-600/90 dark:border-purple-400/30',
            'text' => 'text-purple-600 dark:text-purple-400',
        ],
        'yellow' => [
            'gradient' => 'from-yellow-700 via-yellow-600 to-transparent dark:from-yellow-600 dark:via-yellow-700',
            'border' => 'border-yellow-600/90 dark:border-yellow-400/30',
            'text' => 'text-yellow-600 dark:text-yellow-400',
        ],
    ];

    #[Computed]
    public function winners(): array
    {
        $connection = session('game_db_connection', 'gamedb_main');

        return Cache::remember("hall_of_fame_winners_{$connection}", now()->addWeek(), function () {
            $latestWinners = HallOfFame::first();

            // If no HallOfFame data, return defaults
            if (! $latestWinners) {
                return collect(self::DEFAULT_WINNERS)->map(function ($name, $class) {
                    return [
                        'name' => $name,
                        'class' => $class,
                        'class_name' => self::CHARACTER_CONFIG[$class]['class_name'],
                        'color' => self::CHARACTER_CONFIG[$class]['color'],
                        'image' => "images/characters/{$class}.png",
                        'hof_wins' => 0,
                    ];
                })->values()->toArray();
            }

            // Get actual winners
            return collect(self::CHARACTER_CONFIG)->map(function ($config, $class) use ($latestWinners) {
                $characterName = $latestWinners->$class ?: self::DEFAULT_WINNERS[$class];

                $character = Character::where('Name', $characterName)->first();

                return [
                    'name' => $characterName,
                    'class' => $class,
                    'class_name' => $config['class_name'],
                    'color' => $config['color'],
                    'image' => "images/characters/{$class}.png",
                    'hof_wins' => $character?->HofWins ?? 0,
                ];
            })->values()->toArray();
        });
    }

    protected function getViewName(): string
    {
        return 'pages.guest.rankings.spotlight.players';
    }

    protected function getLayoutType(): string
    {
        return 'guest';
    }
}
