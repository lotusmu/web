<?php

namespace App\Enums\Partner;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum PartnerStatus: string implements HasColor, HasLabel
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case SUSPENDED = 'suspended';

    public function getLabel(): string
    {
        return match ($this) {
            self::ACTIVE => 'Active',
            self::INACTIVE => 'Inactive',
            self::SUSPENDED => 'Suspended',
        };
    }

    public function getColor(): array|string|null
    {
        return match ($this) {
            self::ACTIVE => 'success',
            self::INACTIVE => 'warning',
            self::SUSPENDED => 'danger',
        };
    }
}
