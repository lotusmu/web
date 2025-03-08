<?php

namespace App\Enums\Utility;

enum RankingScoreType: string
{
    case HUNTERS = 'hunters';
    case EVENTS = 'events';

    public function label(): string
    {
        return match ($this) {
            self::HUNTERS => 'Hunt',
            self::EVENTS => 'Event'
        };
    }

    public function scoreTitle(RankingPeriodType $scope): string
    {
        return __("{$scope->label()} {$this->label()} Score");
    }

    public function rulesHeading(): string
    {
        return match ($this) {
            self::EVENTS => __('Event Scoring Rules'),
            self::HUNTERS => __('Hunter Scoring Rules'),
        };
    }

    public function rulesDescription(): string
    {
        return match ($this) {
            self::EVENTS => __('Points awarded for winning in events.'),
            self::HUNTERS => __('Points awarded for monster hunting.'),
        };
    }

    public function model(): string
    {
        return match ($this) {
            self::HUNTERS => 'monster',
            self::EVENTS => 'event'
        };
    }

    public function weeklyRelation(): string
    {
        return match ($this) {
            self::HUNTERS => 'weeklyHunterScores',
            self::EVENTS => 'weeklyEventScores'
        };
    }

    public function totalRelation(): string
    {
        return match ($this) {
            self::HUNTERS => 'hunterScores',
            self::EVENTS => 'eventScores'
        };
    }

    public function weeklyScoreField(): string
    {
        return match ($this) {
            self::HUNTERS => 'HunterScoreWeekly',
            self::EVENTS => 'EventScoreWeekly'
        };
    }

    public function totalScoreField(): string
    {
        return match ($this) {
            self::HUNTERS => 'HunterScore',
            self::EVENTS => 'EventScore'
        };
    }

    public function scoreSchema(): array
    {
        return match ($this) {
            self::HUNTERS => [
                'name_field' => 'MonsterName',
                'count_field' => 'KillCount',
                'points_field' => 'PointsPerKill',
                'count_label' => __('kills'),
            ],
            self::EVENTS => [
                'name_field' => 'EventName',
                'count_field' => 'WinCount',
                'points_field' => 'PointsPerWin',
                'count_label' => __('wins'),
            ]
        };
    }
}
