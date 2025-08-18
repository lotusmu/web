<?php

namespace App\Livewire\Pages\Guest\Rankings\Spotlight;

use App\Enums\Game\ServerVersion;
use App\Livewire\BaseComponent;
use App\Models\Game\Character;
use App\Models\Game\Ranking\HallOfFame;
use App\Models\Utility\GameServer;
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
        'sum' => ['class_name' => 'Summoners', 'color' => 'cyan'],
        'rf' => ['class_name' => 'Fighters', 'color' => 'orange'],
    ];

    public const DEFAULT_WINNERS = [
        'dk' => 'TBD',
        'dw' => 'TBD',
        'fe' => 'TBD',
        'mg' => 'TBD',
        'dl' => 'TBD',
        'sum' => 'TBD',
        'rf' => 'TBD',
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
        'cyan' => [
            'gradient' => 'from-cyan-700 via-cyan-600 to-transparent dark:from-cyan-600 dark:via-cyan-700',
            'border' => 'border-cyan-600/90 dark:border-cyan-400/30',
            'text' => 'text-cyan-600 dark:text-cyan-400',
        ],
        'orange' => [
            'gradient' => 'from-orange-700 via-orange-600 to-transparent dark:from-orange-600 dark:via-orange-700',
            'border' => 'border-orange-600/90 dark:border-orange-400/30',
            'text' => 'text-orange-600 dark:text-orange-400',
        ],
    ];

    #[Computed]
    public function winners(): array
    {
        $connection = session('game_db_connection', 'gamedb_main');
        $server = GameServer::default();
        $serverVersion = $server?->server_version ?? ServerVersion::Season3;

        return Cache::remember("hall_of_fame_winners_{$connection}_{$serverVersion->value}", now()->addWeek(), function () use ($serverVersion) {
            $availableClasses = $this->getAvailableClasses($serverVersion);
            $latestWinners = HallOfFame::first();

            // If no HallOfFame data, return defaults
            if (! $latestWinners) {
                return collect($availableClasses)->map(function ($class) {
                    return [
                        'name' => self::DEFAULT_WINNERS[$class],
                        'class' => $class,
                        'class_name' => self::CHARACTER_CONFIG[$class]['class_name'],
                        'color' => self::CHARACTER_CONFIG[$class]['color'],
                        'image' => "images/characters/{$class}.png",
                        'hof_wins' => 0,
                    ];
                })->values()->toArray();
            }

            // Get actual winners
            return collect($availableClasses)->map(function ($class) use ($latestWinners) {
                $characterName = $latestWinners->$class ?: self::DEFAULT_WINNERS[$class];

                $character = Character::where('Name', $characterName)->first();

                return [
                    'name' => $characterName,
                    'class' => $class,
                    'class_name' => self::CHARACTER_CONFIG[$class]['class_name'],
                    'color' => self::CHARACTER_CONFIG[$class]['color'],
                    'image' => "images/characters/{$class}.png",
                    'hof_wins' => $character?->HofWins ?? 0,
                ];
            })->values()->toArray();
        });
    }

    private function getAvailableClasses(ServerVersion $serverVersion): array
    {
        return match ($serverVersion) {
            ServerVersion::Season6 => ['dk', 'dw', 'fe', 'mg', 'dl', 'sum', 'rf'],
            ServerVersion::Season3 => ['dk', 'dw', 'fe', 'mg', 'dl'],
            default => ['dk', 'dw', 'fe', 'mg', 'dl'],
        };
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
