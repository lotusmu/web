<?php

namespace App\Enums;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum ScheduledEventType: string implements HasColor, HasLabel
{
    case EVENT = 'event';
    case INVASION = 'invasion';

    public function getLabel(): string
    {
        return match ($this) {
            self::EVENT => 'Event',
            self::INVASION => 'Invasion',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::EVENT => Color::Sky,
            self::INVASION => Color::Pink,
        };
    }
}
