<?php

namespace App\Enums\Utility;

use Filament\Support\Contracts\HasLabel;

enum ResourceType: string implements HasLabel
{
    case TOKENS = 'tokens';
    case CREDITS = 'credits';
    case ZEN = 'zen';

    public function getLabel(): string
    {
        return match ($this) {
            self::TOKENS => __('Tokens'),
            self::CREDITS => __('Credits'),
            self::ZEN => 'Zen',
        };
    }

    public function getBadgeColor(): string
    {
        return match ($this) {
            self::TOKENS => 'sky',
            self::CREDITS => 'teal',
            self::ZEN => 'amber',
        };
    }
}
