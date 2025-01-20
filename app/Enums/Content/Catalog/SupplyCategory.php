<?php

namespace App\Enums\Content\Catalog;

use Filament\Support\Contracts\HasLabel;

enum SupplyCategory: string implements HasLabel
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
}
