<?php

namespace App\Enums\Partner;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum ApplicationStatus: string implements HasColor, HasIcon, HasLabel
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';

    public function getLabel(): string
    {
        return match ($this) {
            self::PENDING => 'Pending Review',
            self::APPROVED => 'Approved',
            self::REJECTED => 'Rejected',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::PENDING => 'warning',
            self::APPROVED => 'success',
            self::REJECTED => 'danger',
        };

    }

    public function getIcon(): string
    {
        return match ($this) {
            self::PENDING => 'heroicon-o-clock',
            self::APPROVED => 'heroicon-o-check-circle',
            self::REJECTED => 'heroicon-o-x-circle',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::PENDING => 'sky',
            self::APPROVED => 'emerald',
            self::REJECTED => 'rose',
        };
    }

    public function badgeIcon(): string
    {
        return match ($this) {
            self::PENDING => 'clock',
            self::APPROVED => 'check-circle',
            self::REJECTED => 'x-circle',
        };
    }
}
