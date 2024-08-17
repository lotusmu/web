<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum DatabaseConnection: string implements HasLabel
{
    case GAME_DB_MAIN = 'gamedb_main';
    case GAME_DB_X200 = 'gamedb_x200';

    public static function getOptions(): array
    {
        return array_combine(
            array_column(self::cases(), 'value'),
            array_map(fn ($case) => $case->getLabel(), self::cases())
        );
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::GAME_DB_MAIN => 'Main',
            self::GAME_DB_X200 => 'x200',
        };
    }
}
