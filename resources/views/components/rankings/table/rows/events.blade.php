@php
    use App\Enums\Utility\RankingPeriodType;
@endphp

@include('components.rankings.table.rows.common', ['character' => $character])

<flux:cell>
    <livewire:pages.guest.rankings.character-score-modal
        type="events"
        :character="$character"
        :scope="RankingPeriodType::WEEKLY"
        wire:key="character-{{ $character->Name }}-weekly"
    />
</flux:cell>

<flux:cell>
    <livewire:pages.guest.rankings.character-score-modal
        type="events"
        :character="$character"
        :scope="RankingPeriodType::TOTAL"
        wire:key="character-{{ $character->Name }}-total"
    />
</flux:cell>
