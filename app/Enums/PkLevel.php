<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum PkLevel: int implements HasLabel
{
    case Hero = 1;
    case Commoner = 2;
    case Normal = 3;
    case AgainstMurderer = 4;
    case Murderer = 5;
    case Phonomania = 6;

    public function getLabel(): string
    {
        return match ($this) {
            self::Hero => 'Hero',
            self::Commoner => 'Commoner',
            self::Normal => 'Normal',
            self::AgainstMurderer => 'Against Murderer',
            self::Murderer => 'Murderer',
            self::Phonomania => 'Phonomania',
        };
    }
}
