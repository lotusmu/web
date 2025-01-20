<?php

namespace App\Enums\Content\Catalog;

use Filament\Support\Contracts\HasLabel;

enum BuffDuration: int implements HasLabel
{
    case WEEK = 7;
    case TWO_WEEKS = 14;
    case MONTH = 30;

    public function getLabel(): string
    {
        return match ($this) {
            self::WEEK => '7 days',
            self::TWO_WEEKS => '14 days',
            self::MONTH => '30 days',
        };
    }
}
