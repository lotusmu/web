<?php

namespace App\Enums;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum OrderStatus: string implements HasColor, HasIcon, HasLabel
{
    case PENDING = 'pending';
    case COMPLETED = 'completed';
    case FAILED = 'failed';
    case REFUNDED = 'refunded';
    case EXPIRED = 'expired';

    public function getLabel(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::COMPLETED => 'Completed',
            self::FAILED => 'Failed',
            self::REFUNDED => 'Refunded',
            self::EXPIRED => 'Expired',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::PENDING => Color::Blue,
            self::COMPLETED => Color::Emerald,
            self::FAILED => Color::Red,
            self::REFUNDED => Color::Zinc,
            self::EXPIRED => Color::Amber,
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::PENDING => 'heroicon-o-pause',
            self::COMPLETED => 'heroicon-o-check',
            self::FAILED => 'heroicon-o-x-mark',
            self::REFUNDED => 'heroicon-o-arrow-uturn-left',
            self::EXPIRED => 'heroicon-o-clock',
        };
    }
}
