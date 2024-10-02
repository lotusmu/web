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
}
