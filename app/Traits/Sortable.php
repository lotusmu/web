<?php

namespace App\Traits;

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

        // Guild-specific mappings
        'members' => 'members_count',
        'total-resets' => 'characters_sum_reset_count',
        'castle-siege' => 'CS_Wins',
        'guild-event-score' => 'characters_sum_event_score',
        'guild-hunt-score' => 'characters_sum_hunter_score',
    ];

    private const VALID_DIRECTIONS = ['asc', 'desc'];

    private const GUILD_SORTS = [
        'members',
        'total-resets',
        'castle-siege',
        'guild-event-score',
        'guild-hunt-score',
    ];

    public string $sortBy = 'resets';

    public string $sortDirection = 'desc';

    protected function getDefaultSort(): string
    {
        if (in_array($this->sortBy, self::GUILD_SORTS)) {
            return 'total-resets';
        }

        return 'resets';
    }

    public function mount(): void
    {
        if (! array_key_exists($this->sortBy, self::SORT_MAP)) {
            $this->sortBy = $this->getDefaultSort();
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
        $dbColumn = self::SORT_MAP[$this->sortBy] ?? $this->sortBy;

        if (! $dbColumn) {
            return $query;
        }

        return match ($dbColumn) {
            'members_count' => $query->orderBy('members_count', $this->sortDirection),
            'characters_sum_reset_count' => $query->orderBy('characters_sum_reset_count', $this->sortDirection),
            'CS_Wins' => $query->orderBy('CS_Wins', $this->sortDirection),
            'characters_sum_event_score' => $query->orderBy('characters_sum_event_score', $this->sortDirection),
            'characters_sum_hunter_score' => $query->orderBy('characters_sum_hunter_score', $this->sortDirection),
            default => $query
        };
    }
}
