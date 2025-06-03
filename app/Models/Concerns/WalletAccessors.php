<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait WalletAccessors
{
    protected function credits(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->WCoinC,
            set: fn ($value) => ['WCoinC' => $value]
        );
    }

    protected function gamePoints(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->WCoinP,
            set: fn ($value) => ['WCoinP' => $value]
        );
    }

    protected function luckyTickets(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->GoblinPoint,
            set: fn ($value) => ['GoblinPoint' => $value]
        );
    }
}
