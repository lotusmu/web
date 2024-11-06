<?php

namespace App\Enums\Utility;

use Filament\Support\Contracts\HasLabel;

enum OperationType: string implements HasLabel
{
    case TRANSFER = 'transfer';
    case EXCHANGE = 'exchange';
    case PK_CLEAR = 'pk_clear';
    case STEALTH = 'stealth';

    public function getLabel(): string
    {
        return match ($this) {
            self::TRANSFER => 'Transfer',
            self::EXCHANGE => 'Exchange',
            self::PK_CLEAR => 'PK Clear',
            self::STEALTH => 'Stealth Mode',
        };
    }
}
