@php use App\Enums\Utility\RankingScoreType; @endphp

@props(['guilds'])

<flux:rows>
    @foreach($guilds as $guild)
        <flux:row wire:key="{{ $guild->G_Name }}">
            <x-rankings.table.cells.rank :paginator="$guilds" :$loop/>

            <flux:cell class="flex items-center space-x-2">
                <x-guild-identity :$guild/>
            </flux:cell>

            <flux:cell>
                {{ $guild->members_count }}
            </flux:cell>

            <flux:cell>
                {{ number_format($guild->characters_sum_reset_count ?? 0) }}
            </flux:cell>

            <x-rankings.table.cells.guild-master :character="$guild->master"/>

            <flux:cell>
                {{ $guild->CS_Wins }}
            </flux:cell>

            <x-rankings.table.cells.guild-score
                :$guild
                :score-type="RankingScoreType::EVENTS"
                :score="$guild->characters_sum_event_score"
            />

            <x-rankings.table.cells.guild-score
                :$guild
                :score-type="RankingScoreType::HUNTERS"
                :score="$guild->characters_sum_hunter_score"
            />
        </flux:row>
    @endforeach
</flux:rows>
