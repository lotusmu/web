<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum PkLevel: int implements HasLabel
{
    case Hero = 0;
    case Commoner = 1;
    case Normal = 2;
    case AgainstMurderer = 3;
    case Murderer = 4;
    case Phonomania = 5;

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
