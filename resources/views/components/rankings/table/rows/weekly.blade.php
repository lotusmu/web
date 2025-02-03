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
    <x-guild-identity :guildMember="$character->guildMember"/>
</flux:cell>

<flux:cell>
    <flux:button size="sm" variant="ghost" inset="top bottom" icon-trailing="chevron-down">
        <span>{{ rand(0,5000) }}</span>
    </flux:button>
</flux:cell>

<flux:cell>
    @php
        $type = RankingScoreType::HUNTERS;
        $scope= RankingPeriodType::WEEKLY;
        $modalKey = $type->value . '-score-' . $scope->value . '-' . $character->Name;
    @endphp

    <flux:modal.trigger :name="$modalKey">
        <flux:button size="sm" variant="ghost" inset="top bottom" icon-trailing="chevron-down">
            <span>{{ $character->HunterScoreWeekly }}</span>
        </flux:button>
    </flux:modal.trigger>

    <flux:modal :name="$modalKey" variant="flyout" position="right">
        <livewire:pages.guest.rankings.hunt-score-modal
            :character="$character"
            :scope="$scope"
            :wire:key="$modalKey"
            lazy
        />
    </flux:modal>
</flux:cell>

<flux:cell>
    {{ $character->MapNumber->getLabel() }}
</flux:cell>
