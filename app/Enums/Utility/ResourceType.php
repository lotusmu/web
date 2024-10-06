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
            self::TOKENS => 'Tokens',
            self::CREDITS => 'Credits',
            self::ZEN => 'Zen',
        };
    }
}
