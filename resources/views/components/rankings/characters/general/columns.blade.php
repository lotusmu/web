@php use App\Enums\Utility\RankingScoreType; @endphp

@props([
    'sortBy',
    'sortDirection'
])

<flux:columns>
    <flux:column>#</flux:column>

    <flux:column>{{ __('Character') }}</flux:column>

    <flux:column>{{ __('Class') }}</flux:column>

    <flux:column>{{ __('Level') }}</flux:column>

    <flux:column
        sortable
        :sorted="$sortBy === 'resets'"
        :direction="$sortDirection"
        wire:click="sort('resets')"
    >
        {{ __('Resets') }}
    </flux:column>

    <flux:column
        sortable
        :sorted="$sortBy === 'hof'"
        :direction="$sortDirection"
        wire:click="sort('hof')"
    >
        {{ __('HoF') }}
    </flux:column>

    <flux:column
        sortable
        :sorted="$sortBy === 'quests'"
        :direction="$sortDirection"
        wire:click="sort('quests')"
    >
        {{ __('Quests') }}
    </flux:column>

    <flux:column>{{ __('Guild') }}</flux:column>

    <x-rankings.characters.score-column
        :sort-by="$sortBy"
        :sort-direction="$sortDirection"
        column="event-score"
        :score-type="RankingScoreType::EVENTS"
        label="{{ __('Event Score') }}"
    />

    <x-rankings.characters.score-column
        :sort-by="$sortBy"
        :sort-direction="$sortDirection"
        column="hunt-score"
        :score-type="RankingScoreType::HUNTERS"
        label="{{ __('Hunt Score') }}"
    />

    <flux:column>{{ __('Location') }}</flux:column>
</flux:columns>
