<?php

namespace App\Enums\Content\Catalog;

use Filament\Support\Contracts\HasLabel;

enum EquipmentOption: string implements HasLabel
{
    case LEVEL = 'level';
    case ADDITIONAL = 'additional';
    case LUCK = 'luck';
    case WEAPON_SKILL = 'weapon_skill';
    case EXCELLENT = 'excellent';

    public function getLabel(): string
    {
        return match ($this) {
            self::LEVEL => 'Level',
            self::ADDITIONAL => 'Additional',
            self::LUCK => 'Luck',
            self::WEAPON_SKILL => 'Weapon Skill',
            self::EXCELLENT => 'Excellent'
        };
    }

    public function badgeIcon(): string
    {
        return match ($this) {
            self::LEVEL => 'plus-circle',
            self::ADDITIONAL => 'chevron-double-up',
            self::LUCK => 'star',
            self::WEAPON_SKILL => 'sword',
            self::EXCELLENT => 'bolt',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::LEVEL => 'yellow',
            self::ADDITIONAL, self::WEAPON_SKILL, self::LUCK => 'blue',
            self::EXCELLENT => 'emerald',
        };
    }
}
