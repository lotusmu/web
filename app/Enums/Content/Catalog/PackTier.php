<?php

namespace App\Enums\Content\Catalog;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum PackTier: int implements HasColor, HasLabel
{
    case TIER_1 = 1;
    case TIER_2 = 2;
    case TIER_3 = 3;

    public function getLabel(): string
    {
        return match ($this) {
            self::TIER_1 => __('Tier 1'),
            self::TIER_2 => __('Tier 2'),
            self::TIER_3 => __('Tier 3'),
        };
    }

    public function getColor(): string|array
    {
        return match ($this) {
            self::TIER_1 => Color::Orange,
            self::TIER_2 => Color::Zinc,
            self::TIER_3 => Color::Yellow,
        };
    }
}
