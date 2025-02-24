<?php

namespace App\Enums\Game;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum BanStatus: int implements HasColor, HasIcon, HasLabel
{
    case Active = 0;
    case Banned = 1;
    case GM = 32;

    public function getLabel(): string
    {
        return match ($this) {
            self::Active => 'Active',
            self::Banned => 'Banned',
            self::GM => 'Game Master',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Active => 'success',
            self::Banned => 'danger',
            self::GM => 'primary',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::Active => 'heroicon-o-check-circle',
            self::Banned => 'heroicon-o-lock-closed',
            self::GM => 'heroicon-o-user',
        };
    }
}
