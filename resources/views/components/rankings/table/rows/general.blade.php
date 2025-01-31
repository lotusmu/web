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
    <livewire:pages.guest.rankings.character-score-modal
        :type="RankingScoreType::EVENTS"
        :character="$character"
        :scope="RankingPeriodType::TOTAL"
        wire:key="{{ $character->Name . '-' . RankingPeriodType::TOTAL->value . '-' . RankingScoreType::EVENTS->value }}"
    />
</flux:cell>

<flux:cell>
    <livewire:pages.guest.rankings.character-score-modal
        :type="RankingScoreType::HUNTERS"
        :character="$character"
        :scope="RankingPeriodType::TOTAL"
        wire:key="{{ $character->Name . '-' . RankingPeriodType::TOTAL->value . '-' . RankingScoreType::HUNTERS->value }}"
    />
</flux:cell>

<flux:cell>
    {{ $character->MapNumber->getLabel() }}
</flux:cell>
