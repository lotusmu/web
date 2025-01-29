<?php

namespace App\Enums\Utility;

enum RankingPeriodType: string
{
    case WEEKLY = 'weekly';
    case TOTAL = 'total';

    public function label(): string
    {
        return match ($this) {
            self::WEEKLY => __('Weekly'),
            self::TOTAL => __('Total')
        };
    }
}
