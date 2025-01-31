<?php

namespace App\Enums\Utility;

enum RankingScoreType: string
{
    case HUNTERS = 'hunters';
    case EVENTS = 'events';

    public function label(): string
    {
        return match ($this) {
            self::HUNTERS => __('Hunters'),
            self::EVENTS => __('Events'),
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::HUNTERS => __('Points awarded for monster hunting.'),
            self::EVENTS => __('Points awarded for defeating monsters in events.'),
        };
    }
}
