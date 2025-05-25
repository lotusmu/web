<?php

namespace App\Enums\Partner;

enum PartnerLevel: int
{
    case LEVEL_ONE = 1;
    case LEVEL_TWO = 2;
    case LEVEL_THREE = 3;
    case LEVEL_FOUR = 4;
    case LEVEL_FIVE = 5;

    public function getLabel(): string
    {
        return match ($this) {
            self::LEVEL_ONE => 'Level 1',
            self::LEVEL_TWO => 'Level 2',
            self::LEVEL_THREE => 'Level 3',
            self::LEVEL_FOUR => 'Level 4',
            self::LEVEL_FIVE => 'Level 5',
        };
    }
}
