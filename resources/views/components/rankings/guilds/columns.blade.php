@php use App\Enums\Utility\RankingScoreType; @endphp

@props([
    'sortBy',
    'sortDirection'
])

<flux:columns>
    <flux:column>#</flux:column>

    <flux:column>{{ __('Guild Name') }}</flux:column>

    <flux:column
        sortable
        :sorted="$sortBy === 'members'"
        :direction="$sortDirection"
        wire:click="sort('members')"
    >
        {{ __('Members') }}
    </flux:column>

    <flux:column
        sortable
        :sorted="$sortBy === 'total-resets'"
        :direction="$sortDirection"
        wire:click="sort('total-resets')"
    >
        {{ __('Total Resets') }}
    </flux:column>

    <flux:column>{{ __('Guild Master') }}</flux:column>

    <flux:column
        sortable
        :sorted="$sortBy === 'castle-siege'"
        :direction="$sortDirection"
        wire:click="sort('castle-siege')"
    >
        {{ __('Castle Siege Wins') }}
    </flux:column>

    <x-rankings.guilds.score-column
        :sort-by="$sortBy"
        :sort-direction="$sortDirection"
        column="characters_sum_event_score"
        :score-type="RankingScoreType::EVENTS"
        label="{{ __('Event Score') }}"
    />

    <x-rankings.guilds.score-column
        :sort-by="$sortBy"
        :sort-direction="$sortDirection"
        column="characters_sum_hunter_score"
        :score-type="RankingScoreType::HUNTERS"
        label="{{ __('Hunt Score') }}"
    />
</flux:columns>
