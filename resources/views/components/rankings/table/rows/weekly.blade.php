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
    <livewire:pages.guest.rankings.hunt-score-modal
        :type="RankingScoreType::HUNTERS"
        :character="$character"
        :scope="RankingPeriodType::WEEKLY"
        :wire:key="$this->getScoreKey($character, RankingScoreType::HUNTERS)"
    />
</flux:cell>

<flux:cell>
    {{ $character->MapNumber->getLabel() }}
</flux:cell>
