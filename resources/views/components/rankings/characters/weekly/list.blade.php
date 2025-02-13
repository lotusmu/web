@php
    use App\Enums\Utility\RankingScoreType;
    use App\Enums\Utility\ResourceType;
@endphp

@props([
    'characters',
    'getRewardsForPosition',
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
            <flux:row wire:key="{{ $this->getRowKey($character, 'weekly') }}">
                <x-rankings.table.cells.rank :paginator="$characters" :$loop/>

                <x-rankings.table.cells.character-name :$character/>

                <x-rankings.table.cells.character-class :$character/>

                <flux:cell>{{ $character->cLevel }}</flux:cell>

                <flux:cell>{{ $character->ResetCount }}</flux:cell>

                <flux:cell>
                    <x-guild-identity :guildMember="$character->guildMember"/>
                </flux:cell>

                <x-rankings.table.cells.score
                    :character="$character"
                    :score-type="RankingScoreType::EVENTS"
                    :scope="$period"
                    :score="$character->EventScoreWeekly"
                    :modal-key="$this->getScoreKey($character, RankingScoreType::EVENTS, $period)"
                />

                <x-rankings.table.cells.score
                    :character="$character"
                    :score-type="RankingScoreType::HUNTERS"
                    :scope="$period"
                    :score="$character->HunterScoreWeekly"
                    :modal-key="$this->getScoreKey($character, RankingScoreType::HUNTERS, $period)"
                />

                <flux:cell class="space-x-1">
                    @php $rewards = $getRewardsForPosition($loop->iteration) @endphp

                    @if(empty($rewards))
                        <x-empty-cell/>
                    @else
                        @foreach($rewards as $reward)
                            <x-resource-badge
                                :value="$reward['amount']"
                                :resource="ResourceType::from($reward['type'])"
                            />
                        @endforeach
                    @endif
                </flux:cell>
            </flux:row>
        @endforeach
    @endif
</flux:rows>
