<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait CreditAccessors
{
    protected function value(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->WCoinC,
            set: fn ($value) => ['WCoinC' => $value]
        );
    }
}
