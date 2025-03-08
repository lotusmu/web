<?php

namespace App\Enums\Ticket;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum TicketStatus: string implements HasColor, HasIcon, HasLabel
{
    case NEW = 'new';
    case IN_PROGRESS = 'in_progress';
    case ON_HOLD = 'on_hold';
    case PENDING = 'pending';
    case RESOLVED = 'resolved';
    case CLOSED = 'closed';

    public function getLabel(): string
    {
        return match ($this) {
            self::NEW => __('New'),
            self::IN_PROGRESS => __('In Progress'),
            self::ON_HOLD => __('On Hold'),
            self::PENDING => __('Pending'),
            self::RESOLVED => __('Resolved'),
            self::CLOSED => __('Closed'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::NEW => Color::Emerald,
            self::IN_PROGRESS => Color::Blue,
            self::ON_HOLD => Color::Amber,
            self::PENDING => Color::Orange,
            self::RESOLVED => Color::Teal,
            self::CLOSED => Color::Zinc,
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::NEW => 'emerald',
            self::IN_PROGRESS => 'blue',
            self::ON_HOLD => 'amber',
            self::PENDING => 'orange',
            self::RESOLVED => 'teal',
            self::CLOSED => 'zinc',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::NEW => 'heroicon-o-plus-circle',
            self::IN_PROGRESS => 'heroicon-o-play',
            self::ON_HOLD => 'heroicon-o-pause',
            self::PENDING => 'heroicon-o-clock',
            self::RESOLVED => 'heroicon-o-check-circle',
            self::CLOSED => 'heroicon-o-x-circle',
        };
    }

    public function icon(): ?string
    {
        return match ($this) {
            self::NEW => 'plus-circle',
            self::IN_PROGRESS => 'play',
            self::ON_HOLD => 'pause',
            self::PENDING => 'clock',
            self::RESOLVED => 'check-circle',
            self::CLOSED => 'x-circle',
        };
    }
}
