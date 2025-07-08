@php use App\Enums\Utility\RankingScoreType; @endphp

@props([
    'characters',
    'period'
])

<flux:rows>
    @if($characters->isEmpty())
        <flux:row>
            <flux:cell colspan="100%">
                {{ __('No characters found.') }}
            </flux:cell>
        </flux:row>
    @else
        @foreach($characters as $character)
            <flux:row wire:key="{{ $this->getRowKey($character, 'general') }}">
                <x-rankings.table.cells.rank :paginator="$characters" :$loop/>

                <x-rankings.table.cells.character-name :$character/>

                <x-rankings.table.cells.character-class :$character :label="false"/>

                <flux:cell>{{ $character->cLevel }}</flux:cell>

                <flux:cell>{{ $character->ResetCount }}</flux:cell>

                <flux:cell>{{ $character->HofWins }}</flux:cell>

                <flux:cell>{{ $character->quest_count }}</flux:cell>

                <flux:cell>{{ $character->total_achievement_points }}</flux:cell>

                <flux:cell>
                    <x-guild-identity :guildMember="$character->guildMember"/>
                </flux:cell>

                <x-rankings.table.cells.score
                    :character="$character"
                    :score-type="RankingScoreType::EVENTS"
                    :scope="$period"
                    :score="$character->EventScore"
                    :modal-key="$this->getScoreKey($character, RankingScoreType::EVENTS, $period)"
                />

                <x-rankings.table.cells.score
                    :character="$character"
                    :score-type="RankingScoreType::HUNTERS"
                    :scope="$period"
                    :score="$character->HunterScore"
                    :modal-key="$this->getScoreKey($character, RankingScoreType::HUNTERS, $period)"
                />

                <flux:cell>{{ $character->getDisplayLocation() }}</flux:cell>
            </flux:row>
        @endforeach
    @endif
</flux:rows>
