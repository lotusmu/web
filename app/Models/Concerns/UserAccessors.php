<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Number;

trait UserAccessors
{
    private function format($value): object
    {
        return new class($value)
        {
            public function __construct(private int $value) {}

            public function __toString(): string
            {
                return (string) $this->value;
            }

            public function format(): string
            {
                return Number::format($this->value, locale: 'bg');
            }
        };
    }

    protected function tokens(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->format($this->member->tokens ?? 0)
        );
    }

    protected function credits(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->format($this->member->wallet->credits ?? 0)
        );
    }

    protected function zen(): Attribute
    {
        return Attribute::make(
            get: fn () => new class($this->member->wallet->zen ?? 0)
            {
                public function __construct(private int $value) {}

                public function __toString(): string
                {
                    return (string) $this->value;
                }

                public function format(): string
                {
                    return Number::abbreviate($this->value, precision: 2);
                }
            }
        );
    }
}
