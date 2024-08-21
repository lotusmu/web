<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum TicketStatus: string implements HasLabel
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
}
