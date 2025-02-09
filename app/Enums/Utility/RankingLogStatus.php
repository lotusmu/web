<?php

namespace App\Enums\Utility;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum RankingLogStatus: string implements HasColor, HasIcon, HasLabel
{
    case STARTED = 'started';
    case SUCCESS = 'success';
    case FAILED = 'failed';
    case SKIPPED = 'skipped';

    public function getLabel(): string
    {
        return match ($this) {
            self::STARTED => 'In Progress',
            self::SUCCESS => 'Success',
            self::FAILED => 'Failed',
            self::SKIPPED => 'Skipped',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::STARTED => Color::Blue,
            self::SUCCESS => Color::Emerald,
            self::FAILED => Color::Rose,
            self::SKIPPED => Color::Amber,
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::STARTED => 'heroicon-o-play',
            self::SUCCESS => 'heroicon-o-check',
            self::FAILED => 'heroicon-o-x-mark',
            self::SKIPPED => 'heroicon-o-forward',
        };
    }
}
