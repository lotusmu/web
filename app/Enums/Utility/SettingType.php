<?php

namespace App\Enums\Utility;

use Filament\Support\Contracts\HasLabel;

enum SettingType: string implements HasLabel
{
    case COST = 'cost';
    case PERCENTAGE = 'percentage';
    case DURATION = 'duration';
    case RESOURCE = 'resource';

    public function getLabel(): string
    {
        return match ($this) {
            self::COST => 'Flat Cost',
            self::PERCENTAGE => 'Percentage Rate',
            self::DURATION => 'Duration (Days)',
            self::RESOURCE => 'Resource Type',
        };
    }

    public function needsValue(): bool
    {
        return match ($this) {
            self::COST, self::PERCENTAGE, self::DURATION => true,
            self::RESOURCE => false,
        };
    }
}
