<?php

namespace App\Enums;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum TicketStatus: string implements HasColor, HasLabel
{
    case OPEN = 'open';
    case IN_PROGRESS = 'in_progress';
    case CLOSED = 'closed';

    public function getLabel(): string
    {
        return match ($this) {
            self::OPEN => 'Open',
            self::IN_PROGRESS => 'In Progress',
            self::CLOSED => 'Closed',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::OPEN => Color::Green,
            self::IN_PROGRESS => 'primary',
            self::CLOSED => Color::Red,
        };
    }
}
