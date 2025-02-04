@php
    use App\Enums\Utility\RankingPeriodType;
    use App\Enums\Utility\RankingScoreType;
@endphp

<x-rankings.table.cells.character-name :$character/>

<x-rankings.table.cells.character-class :$character/>

<flux:cell>
    {{ $character->cLevel }}
</flux:cell>

<flux:cell>
    {{ $character->ResetCount }}
</flux:cell>

<flux:cell>
    {{ rand(0,5) }}
</flux:cell>

<flux:cell>
    {{ rand(0,320) }}
</flux:cell>

<flux:cell>
    <x-guild-identity :guildMember="$character->guildMember"/>
</flux:cell>

<flux:cell>
    @php
        $eventModalKey =  RankingScoreType::EVENTS->value . '-score-' . RankingPeriodType::TOTAL->value . '-' . $character->Name;
    @endphp

    @if( $character->EventScore > 0)
        <flux:modal.trigger :name="$eventModalKey">
            <flux:button size="sm" variant="ghost" inset="top bottom" icon-trailing="chevron-down">
                <span>{{ $character->EventScore }}</span>
            </flux:button>
        </flux:modal.trigger>
    @else
        <flux:button size="sm" variant="ghost" inset="top bottom" icon-trailing="chevron-down">
            <span>{{ $character->EventScore }}</span>
        </flux:button>
    @endif


    <flux:modal :name="$eventModalKey" variant="flyout" position="right">
        <livewire:pages.guest.rankings.event-score-modal
            :character="$character"
            :scope="RankingPeriodType::TOTAL"
            :wire:key="$eventModalKey"
            lazy
        />
    </flux:modal>
</flux:cell>

<flux:cell>
    @php
        $hunterModalKey = RankingScoreType::HUNTERS->value . '-score-' . RankingPeriodType::TOTAL->value . '-' . $character->Name;
    @endphp

    @if( $character->HunterScore > 0)
        <flux:modal.trigger :name="$hunterModalKey">
            <flux:button size="sm" variant="ghost" inset="top bottom" icon-trailing="chevron-down">
                <span>{{ $character->HunterScore }}</span>
            </flux:button>
        </flux:modal.trigger>
    @else
        <flux:button size="sm" variant="ghost" inset="top bottom" icon-trailing="chevron-down">
            <span>{{ $character->HunterScore }}</span>
        </flux:button>
    @endif

    <flux:modal :name="$hunterModalKey" variant="flyout" position="right">
        <livewire:pages.guest.rankings.hunt-score-modal
            :character="$character"
            :scope="RankingPeriodType::TOTAL"
            :wire:key="$hunterModalKey"
            lazy
        />
    </flux:modal>
</flux:cell>

<flux:cell>
    {{ $character->MapNumber->getLabel() }}
</flux:cell>
