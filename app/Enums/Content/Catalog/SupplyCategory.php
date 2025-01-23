<?php

namespace App\Enums\Content\Catalog;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum SupplyCategory: string implements HasColor, HasLabel
{
    case CONSUMABLES = 'consumables';
    case SCROLLS_AND_ORBS = 'scrolls_and_orbs';
    case PETS_AND_WINGS = 'pets_and_wings';

    public function getLabel(): string
    {
        return match ($this) {
            self::CONSUMABLES => 'Consumables',
            self::SCROLLS_AND_ORBS => 'Scrolls & Orbs',
            self::PETS_AND_WINGS => 'Pets & Wings'
        };
    }

    public function getColor(): string|array
    {
        return match ($this) {
            self::CONSUMABLES => Color::Fuchsia,
            self::SCROLLS_AND_ORBS => Color::Amber,
            self::PETS_AND_WINGS => Color::Cyan,
        };
    }
}
