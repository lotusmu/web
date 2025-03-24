<?php

namespace App\Enums\Survey;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum MUOnlineForum: string implements HasColor, HasLabel
{
    case DarksTeam = 'darksteam';
    case RageZone = 'ragezone';
    case TUServerMU = 'tuservermu';
    case Other = 'other';

    public function getLabel(): string
    {
        return match ($this) {
            self::DarksTeam => __('DarksTeam Forum'),
            self::RageZone => __('RageZone Forum'),
            self::TUServerMU => __('TU Server Forum'),
            self::Other => __('Other Forum'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::DarksTeam => Color::Purple,
            self::RageZone => Color::Orange,
            self::TUServerMU => Color::Cyan,
            self::Other => Color::Gray,
        };
    }
}
