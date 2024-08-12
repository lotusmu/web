<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum AccountLevel: int implements HasLabel
{
    case Regular = 0;
    case Bronze = 1;
    case Silver = 2;
    case Gold = 3;


    public function getLabel(): string
    {
        return match ($this) {
            self::Regular => 'Regular',
            self::Bronze => 'Bronze',
            self::Silver => 'Silver',
            self::Gold => 'Gold',
        };
    }
}
