<?php

namespace App\Enums\Utility;

use Filament\Support\Contracts\HasLabel;

enum OperationType: string implements HasLabel
{
    case TRANSFER = 'transfer';
    case EXCHANGE = 'exchange';

    public function getLabel(): string
    {
        return match ($this) {
            self::TRANSFER => 'Transfer',
            self::EXCHANGE => 'Exchange',
        };
    }
}
