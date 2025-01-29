<?php

namespace App\Enums\Utility;

enum RankingType: string
{
    case GENERAL = 'general';
    case EVENTS = 'events';
    case HUNTERS = 'hunters';

    public function label(): string
    {
        return match ($this) {
            self::GENERAL => __('General'),
            self::EVENTS => __('Events'),
            self::HUNTERS => __('Hunters'),
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
