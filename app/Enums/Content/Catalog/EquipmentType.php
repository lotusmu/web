<?php

namespace App\Enums\Content\Catalog;

use Filament\Support\Contracts\HasLabel;

enum EquipmentType: string implements HasLabel
{
    case WEAPON = 'weapon';
    case ITEM_SET = 'item_set';
    case ACCESSORY = 'accessory';
    case CONSUMABLE = 'consumable';

    public function getLabel(): string
    {
        return match ($this) {
            self::WEAPON => 'Weapon',
            self::ITEM_SET => 'Item Set',
            self::ACCESSORY => 'Accessory',
            self::CONSUMABLE => 'Consumable'
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::WEAPON => 'sword',
            self::ITEM_SET => 'shield',
            self::ACCESSORY => 'ribbon',
            self::CONSUMABLE => 'beaker'
        };
    }
}
