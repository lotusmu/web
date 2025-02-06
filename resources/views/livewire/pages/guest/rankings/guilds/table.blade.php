<?php

use App\Enums\Game\AccountLevel;
use App\Models\Game\Character;
use App\Models\Game\Guild;
use App\Traits\Sortable;
use Livewire\Attributes\Computed;
use App\Enums\Utility\RankingScoreType;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use App\Traits\Searchable;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

new #[Layout('layouts.guest')] class extends Component {
    use WithPagination;
    use WithoutUrlPagination;
    use Searchable;
    use Sortable;

    public function mount()
    {
        $this->sortBy = 'members';
    }

    #[Computed]
    public function guilds()
    {
        $query = Guild::query()
            ->select([
                'Guild.G_Name',
                'Guild.G_Mark',
                'Guild.G_Master',
                'Guild.CS_Wins',
            ])
            ->withCount('members')
            ->withSum('characters', 'ResetCount')
            ->withSum('characters', 'EventScore')
            ->withSum('characters', 'HunterScore')
            ->with([
                'master:Name,AccountID,Class',
                'master.member:memb___id,AccountLevel',
            ]);

        $query = $this->applySearch($query);
        $query = $this->applySorting($query);

        return $query->simplePaginate(50);
    }

    protected function applySearch($query)
    {
        return $this->searchGuild($query);
    }

    protected function applySorting($query)
    {
        return $this->sortGuilds($query);
    }

    public function placeholder()
    {
        return view('livewire.pages.guest.rankings.players.placeholders.table');
    }
} ?>

<div class="overflow-x-auto relative space-y-8">
    <x-rankings.search wire:model.live.debounce="search"/>

    <flux:table wire:loading.class="opacity-50">
        <flux:columns>
            <flux:column>
                {{ __('Guild Name') }}
            </flux:column>

            <flux:column sortable :sorted="$sortBy === 'members'" :direction="$sortDirection"
                         wire:click="sort('members')">
                {{ __('Members') }}
            </flux:column>

            <flux:column sortable>
                {{ __('Total Resets') }}
            </flux:column>

            <flux:column>
                {{ __('Guild Master') }}
            </flux:column>

            <flux:column sortable>
                {{ __('Castle Siege Wins') }}
            </flux:column>

            <flux:column>
                <flux:table.sortable
                    wire:click="sort('event-score')"
                    :sorted="$sortBy === 'event-score'"
                    :direction="$sortDirection"
                    class="flex items-center gap-2">
                    <span>{{ __('Event Score') }}</span>
                </flux:table.sortable>

                <x-rankings.scoring-rules-trigger :score-type="RankingScoreType::EVENTS"/>
            </flux:column>

            <flux:column>
                <flux:table.sortable
                    wire:click="sort('hunt-score')"
                    :sorted="$sortBy === 'hunt-score'"
                    :direction="$sortDirection"
                    class="flex items-center gap-2">
                    <span>{{ __('Hunt Score') }}</span>
                </flux:table.sortable>

                <x-rankings.scoring-rules-trigger :score-type="RankingScoreType::HUNTERS"/>
            </flux:column>

        </flux:columns>

        <flux:rows>
            @foreach($this->guilds as $guild)
                <flux:row wire:key="{{ $guild->G_Name }}">
                    <flux:cell class="flex items-center space-x-2">
                        <span>
                            {{'No'}}.
                        </span>

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
    </flux:table>

    <div>
        <flux:pagination :paginator="$this->guilds" class="!border-0"/>
    </div>
</div>
