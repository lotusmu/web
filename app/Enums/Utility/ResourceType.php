<?php

namespace App\Enums\Utility;

use Filament\Support\Contracts\HasLabel;

enum ResourceType: string implements HasLabel
{
    case TOKENS = 'tokens';
    case CREDITS = 'credits';
    case GAME_POINTS = 'game_points';
    case LUCKY_TICKETS = 'lucky_tickets';
    case ZEN = 'zen';

    public function getLabel(): string
    {
        return match ($this) {
            self::TOKENS => __('Tokens'),
            self::CREDITS => __('Credits'),
            self::GAME_POINTS => __('Game Points'),
            self::LUCKY_TICKETS => __('Lucky Tickets'),
            self::ZEN => 'Zen',
        };
    }

    public function getBadgeColor(): string
    {
        return match ($this) {
            self::TOKENS => 'sky',
            self::CREDITS => 'teal',
            self::GAME_POINTS => 'rose',
            self::LUCKY_TICKETS => 'green',
            self::ZEN => 'amber',
        };
    }
}
