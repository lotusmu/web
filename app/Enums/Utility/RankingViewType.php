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

    public function getColumnsPath(): string
    {
        return "components.rankings.table.columns.{$this->value}";
    }

    public function getRowsPath(): string
    {
        return "components.rankings.table.rows.{$this->value}";
    }
}
