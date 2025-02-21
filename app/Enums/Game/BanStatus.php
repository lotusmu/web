<?php

namespace App\Enums\Game;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum BanStatus: int implements HasColor, HasIcon, HasLabel
{
    case Active = 0;
    case Banned = 1;

    public function getLabel(): string
    {
        return match ($this) {
            self::Active => 'Active',
            self::Banned => 'Banned',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Active => 'success',
            self::Banned => 'danger',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::Active => 'heroicon-o-check-circle',
            self::Banned => 'heroicon-o-lock-closed',
        };
    }
}
