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

    <flux:column>{{ __('Resets') }}</flux:column>

    <flux:column>{{ __('Guild') }}</flux:column>

    <x-rankings.characters.score-column
        :sort-by="$sortBy"
        :sort-direction="$sortDirection"
        column="weekly-event-score"
        :score-type="RankingScoreType::EVENTS"
        label="{{ __('Weekly Event Score') }}"
    />

    <x-rankings.characters.score-column
        :sort-by="$sortBy"
        :sort-direction="$sortDirection"
        column="weekly-hunt-score"
        :score-type="RankingScoreType::HUNTERS"
        label="{{ __('Weekly Hunt Score') }}"
    />

    <flux:column>
        <span class="mr-1">{{ __('Reward') }}</span>

        <flux:modal.trigger name="rewards-distribution">
            <flux:button variant="ghost" icon="information-circle" size="sm" inset="top bottom"/>
        </flux:modal.trigger>

        <flux:modal name="rewards-distribution" class="w-96 space-y-6">
            <livewire:pages.guest.rankings.players.reward-modal lazy/>
        </flux:modal>

        <flux:separator vertical class="mx-1"/>

        <flux:button
            variant="ghost"
            icon="history"
            size="sm"
            inset="top bottom"
            href="{{ route('rankings.archive') }}"
            wire:navigate.hover
        >
            {{ __('Archive') }}
        </flux:button>
    </flux:column>
</flux:columns>
