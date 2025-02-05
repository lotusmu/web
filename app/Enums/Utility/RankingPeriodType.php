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

    public function scoreField(RankingScoreType $type): string
    {
        return match ($this) {
            self::WEEKLY => $type->weeklyScoreField(),
            self::TOTAL => $type->totalScoreField(),
        };
    }

    public function relationName(RankingScoreType $type): string
    {
        return match ($this) {
            self::WEEKLY => $type->weeklyRelation(),
            self::TOTAL => $type->totalRelation(),
        };
    }
}
