<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait MemberAccessors
{
    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->memb___id,
            set: fn ($value) => ['memb___id' => $value]
        );
    }

    protected function password(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->memb__pwd,
            set: fn ($value) => ['memb__pwd' => $value]
        );
    }

    protected function email(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->mail_addr,
            set: fn ($value) => ['mail_addr' => $value]
        );
    }
}
