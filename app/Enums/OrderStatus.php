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
    case CANCELLED = 'cancelled';
    case DELETED = 'deleted';

    public function getLabel(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::COMPLETED => 'Completed',
            self::FAILED => 'Failed',
            self::REFUNDED => 'Refunded',
            self::EXPIRED => 'Expired',
            self::CANCELLED => 'Cancelled',
            self::DELETED => 'Deleted',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::PENDING => Color::Blue,
            self::COMPLETED => Color::Emerald,
            self::FAILED => Color::Rose,
            self::REFUNDED => Color::Fuchsia,
            self::EXPIRED => Color::Amber,
            self::CANCELLED => Color::Zinc,
            self::DELETED => Color::Red,
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::PENDING => 'heroicon-o-pause',
            self::COMPLETED => 'heroicon-o-check',
            self::FAILED, self::CANCELLED => 'heroicon-o-x-mark',
            self::REFUNDED => 'heroicon-o-arrow-uturn-left',
            self::EXPIRED => 'heroicon-o-clock',
            self::DELETED => 'heroicon-o-trash',
        };
    }

    public static function toArray(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($status) => [$status->value => $status->getLabel()])
            ->toArray();
    }
}
