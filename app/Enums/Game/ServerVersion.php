<?php

namespace App\Enums\Game;

use Filament\Support\Contracts\HasLabel;

enum ServerVersion: string implements HasLabel
{
    case Season3 = 'Season 3';
    case Season6 = 'Season 6';

    public function getLabel(): string
    {
        return match ($this) {
            self::Season3 => __('Season 3'),
            self::Season6 => __('Season 6'),
        };
    }
}
