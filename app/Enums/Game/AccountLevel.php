<?php

namespace App\Enums\Game;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum AccountLevel: int implements HasColor, HasIcon, HasLabel
{
    case Regular = 0;
    case Bronze = 1;
    case Silver = 2;
    case Gold = 3;

    public function getLabel(): string
    {
        return match ($this) {
            self::Regular => 'Regular',
            self::Bronze => 'Bronze',
            self::Silver => 'Silver',
            self::Gold => 'Gold',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Regular => null,
            self::Bronze => Color::Orange,
            self::Silver => Color::Zinc,
            self::Gold => Color::Yellow,
        };
    }

    public function badgeColor(): string|array|null
    {
        return match ($this) {
            self::Regular => null,
            self::Bronze => 'orange',
            self::Silver => 'zinc',
            self::Gold => 'yellow',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Regular => null,
            default => 'heroicon-o-fire',
        };
    }
}
