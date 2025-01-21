<?php

namespace App\Enums\Content\Catalog;

use Filament\Support\Contracts\HasLabel;

enum PackTier: int implements HasLabel
{
    case TIER_1 = 1;
    case TIER_2 = 2;
    case TIER_3 = 3;

    public function getLabel(): string
    {
        return match ($this) {
            self::TIER_1 => 'Tier 1',
            self::TIER_2 => 'Tier 2',
            self::TIER_3 => 'Tier 3',
        };
    }
}
