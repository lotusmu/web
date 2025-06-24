<?php

namespace App\Enums\Partner;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum PartnerLevel: int implements HasColor, HasLabel
{
    case LEVEL_ONE = 1;
    case LEVEL_TWO = 2;
    case LEVEL_THREE = 3;
    case LEVEL_FOUR = 4;
    case LEVEL_FIVE = 5;

    public function getLabel(): string
    {
        return match ($this) {
            self::LEVEL_ONE => 'Level 1',
            self::LEVEL_TWO => 'Level 2',
            self::LEVEL_THREE => 'Level 3',
            self::LEVEL_FOUR => 'Level 4',
            self::LEVEL_FIVE => 'Level 5',
        };
    }

    public function getColor(): array
    {
        return match ($this) {
            self::LEVEL_ONE => Color::Zinc,
            self::LEVEL_TWO => Color::Blue,
            self::LEVEL_THREE => Color::Green,
            self::LEVEL_FOUR => Color::Purple,
            self::LEVEL_FIVE => Color::Yellow,
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::LEVEL_ONE => 'zinc',
            self::LEVEL_TWO => 'blue',
            self::LEVEL_THREE => 'green',
            self::LEVEL_FOUR => 'purple',
            self::LEVEL_FIVE => 'yellow',
        };
    }

    public function getTokenPercentage(): float
    {
        return match ($this) {
            self::LEVEL_ONE => 10.00,
            self::LEVEL_TWO => 15.00,
            self::LEVEL_THREE => 20.00,
            self::LEVEL_FOUR => 25.00,
            self::LEVEL_FIVE => 30.00,
        };
    }

    public static function getOptionsWithPercentages(): array
    {
        $options = [];
        foreach (self::cases() as $level) {
            $options[$level->value] = $level->getLabel().' ('.$level->getTokenPercentage().'%)';
        }

        return $options;
    }
}
