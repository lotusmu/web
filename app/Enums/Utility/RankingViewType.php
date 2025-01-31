<?php

namespace App\Enums\Utility;

enum RankingViewType: string
{
    case GENERAL = 'general';
    case WEEKLY = 'weekly';

    public function label(): string
    {
        return match ($this) {
            self::GENERAL => __('General'),
            self::WEEKLY => __('Weekly'),
        };
    }
}
