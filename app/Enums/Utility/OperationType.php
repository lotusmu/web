<?php

namespace App\Enums\Utility;

use Filament\Support\Contracts\HasLabel;

enum OperationType: string implements HasLabel
{
    case TRANSFER = 'transfer';

    public function getLabel(): string
    {
        return match ($this) {
            self::TRANSFER => 'Transfer',
        };
    }
}
