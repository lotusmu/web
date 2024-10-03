<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Number;

trait UserAccessors
{
    private function format($value, string $method = 'format', int $precision = 0): object
    {
        return new class($value, $method, $precision)
        {
            public function __construct(
                private readonly int $value,
                private readonly string $method,
                private readonly int $precision
            ) {}

            public function __toString(): string
            {
                return (string) $this->value;
            }

            public function format(): string
            {
                return match ($this->method) {
                    'abbreviate' => Number::abbreviate($this->value, precision: $this->precision),

                    default => Number::format($this->value, locale: 'bg'),
                };
            }
        };
    }

    protected function tokens(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->format($this->member->tokens)
        );
    }

    protected function credits(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->format($this->member->wallet->credits)
        );
    }

    protected function zen(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->format($this->member->wallet->zen, 'abbreviate', 2)
        );
    }
}
