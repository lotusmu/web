<?php

namespace App\Traits;

trait Sortable
{
    public $sortBy = 'ResetCount';

    public $sortDirection = 'desc';

    public function sort($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'desc';
        }
    }

    protected function applySorting($query)
    {
        if (! $this->sortBy) {
            return $query;
        }

        return match ($this->sortBy) {
            'ResetCount' => $query->orderBy('ResetCount', $this->sortDirection)
                ->orderBy('cLevel', $this->sortDirection)
                ->orderBy('HofWins', $this->sortDirection),

            'HofWins', 'HunterScore', 'EventScore', 'HunterScoreWeekly', 'EventScoreWeekly' => $query->orderBy($this->sortBy, $this->sortDirection),

            'QuestCount' => $query->orderBy(function ($query) {
                return $query->select('Quest')
                    ->from('CustomQuest')
                    ->whereColumn('CustomQuest.Name', 'Character.Name')
                    ->limit(1);
            }, $this->sortDirection),

            default => $query
        };
    }
}
