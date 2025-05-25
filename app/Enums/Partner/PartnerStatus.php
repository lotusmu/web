<?php

namespace App\Enums\Partner;

use Filament\Support\Contracts\HasLabel;

enum PartnerStatus: string implements HasLabel
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
}
