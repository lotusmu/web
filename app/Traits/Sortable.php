<?php

namespace App\Traits;

use Livewire\Attributes\Url;

trait Sortable
{
    private const SORT_MAP = [
        'resets' => 'ResetCount',
        'hof' => 'HofWins',
        'quests' => 'QuestCount',
        'hunt-score' => 'HunterScore',
        'event-score' => 'EventScore',
        'weekly-hunt-score' => 'HunterScoreWeekly',
        'weekly-event-score' => 'EventScoreWeekly',
        'members' => 'members_count',
    ];

    private const VALID_DIRECTIONS = ['asc', 'desc'];

    #[Url(as: 'sort')]
    public string $sortBy = 'resets';

    #[Url(as: 'direction')]
    public string $sortDirection = 'desc';

    public function mount(): void
    {
        if (! array_key_exists($this->sortBy, self::SORT_MAP)) {
            $this->sortBy = 'resets';
        }

        if (! in_array($this->sortDirection, self::VALID_DIRECTIONS)) {
            $this->sortDirection = 'desc';
        }
    }

    public function sort($column): void
    {
        $urlColumn = array_flip(self::SORT_MAP)[$column] ?? $column;

        if ($this->sortBy === $urlColumn) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $urlColumn;
            $this->sortDirection = 'desc';
        }
    }

    protected function sortCharacters($query)
    {
        $dbColumn = self::SORT_MAP[$this->sortBy] ?? $this->sortBy;

        if (! $dbColumn) {
            return $query;
        }

        return match ($dbColumn) {
            'ResetCount' => $query->orderBy('ResetCount', $this->sortDirection)
                ->orderBy('cLevel', $this->sortDirection)
                ->orderBy('HofWins', $this->sortDirection),

            'HofWins', 'HunterScore', 'EventScore', 'HunterScoreWeekly', 'EventScoreWeekly' => $query->orderBy($dbColumn, $this->sortDirection),

            'QuestCount' => $query->orderBy(function ($query) {
                return $query->select('Quest')
                    ->from('CustomQuest')
                    ->whereColumn('CustomQuest.Name', 'Character.Name')
                    ->limit(1);
            }, $this->sortDirection),

            default => $query
        };
    }

    protected function sortGuilds($query)
    {
        return $query;
    }
}
