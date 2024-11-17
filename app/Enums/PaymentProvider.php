<?php

namespace App\Enums;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum PaymentProvider: string implements HasColor, HasLabel
{
    case STRIPE = 'stripe';

    public function getLabel(): string
    {
        return match ($this) {
            self::STRIPE => 'Stripe',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::STRIPE => Color::Purple,
        };
    }
}
