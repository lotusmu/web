<?php

namespace App\Enums\Utility;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum UpdateBannerType: string implements HasColor, HasIcon, HasLabel
{
    case LATEST = 'latest';
    case NEW = 'new';
    case IMPORTANT = 'important';
    case MAINTENANCE = 'maintenance';
    case ANNOUNCEMENT = 'announcement';

    public function getLabel(): string
    {
        return match ($this) {
            self::LATEST => 'Latest Updates',
            self::NEW => 'New Feature',
            self::IMPORTANT => 'Important',
            self::MAINTENANCE => 'Maintenance',
            self::ANNOUNCEMENT => 'Top Banner Announcement',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::LATEST => Color::Indigo,
            self::NEW => Color::Emerald,
            self::IMPORTANT => Color::Orange,
            self::MAINTENANCE => Color::Rose,
            self::ANNOUNCEMENT => Color::Yellow,
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::LATEST => 'indigo',
            self::NEW => 'emerald',
            self::IMPORTANT => 'orange',
            self::MAINTENANCE => 'rose',
            self::ANNOUNCEMENT => 'yellow',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::LATEST => 'heroicon-o-megaphone',
            self::NEW => 'heroicon-o-sparkles',
            self::IMPORTANT => 'heroicon-o-exclamation-triangle',
            self::MAINTENANCE => 'heroicon-o-wrench',
            self::ANNOUNCEMENT => 'heroicon-o-bell-alert',
        };
    }

    public function icon(): ?string
    {
        return match ($this) {
            self::LATEST => 'megaphone',
            self::NEW => 'sparkles',
            self::IMPORTANT => 'exclamation-triangle',
            self::MAINTENANCE => 'wrench',
            self::ANNOUNCEMENT => 'bell-alert',
        };
    }
}
