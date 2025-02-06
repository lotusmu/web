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
            ->withSum('characters', 'EventScore')  // This matches Character table column
            ->withSum('characters', 'HunterScore') // This matches Character table column
            ->with([
                'master.member',
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

            <flux:column>
                {{ __('Guild Master') }}
            </flux:column>

            <flux:column sortable :sorted="$sortBy === 'members'" :direction="$sortDirection"
                         wire:click="sort('members')">
                {{ __('Members') }}
            </flux:column>

            <flux:column sortable>
                {{ __('Total Resets') }}
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

                    <x-rankings.table.cells.guild-master :character="$guild->master"/>

                    <flux:cell>
                        {{ $guild->members_count }}
                    </flux:cell>

                    <flux:cell>
                        {{ number_format($guild->characters_sum_reset_count ?? 0) }}
                    </flux:cell>

                    <flux:cell>
                        {{ $guild->CS_Wins }}
                    </flux:cell>

                    <flux:cell>
                        @if($guild->characters_sum_event_score > 0)
                            <flux:modal.trigger name="guild-event-score-{{ $guild->G_Name }}">
                                <flux:button size="sm" variant="ghost" inset="top bottom" icon-trailing="chevron-down">
                                    <span>{{ number_format($guild->characters_sum_event_score) }}</span>
                                </flux:button>
                            </flux:modal.trigger>
                        @else
                            <flux:button size="sm" variant="ghost" inset="top bottom" icon-trailing="chevron-down">
                                <span>0</span>
                            </flux:button>
                        @endif

                        <flux:modal name="guild-event-score-{{ $guild->G_Name }}" variant="flyout" position="right">
                            <livewire:pages.guest.rankings.guilds.score-modal
                                :guild="$guild"
                                :type="RankingScoreType::EVENTS"
                                :wire:key="'guild-event-'.$guild->G_Name"
                                lazy
                            />
                        </flux:modal>
                    </flux:cell>

                    <flux:cell>
                        @if($guild->characters_sum_hunter_score > 0)
                            <flux:modal.trigger name="guild-hunter-score-{{ $guild->G_Name }}">
                                <flux:button size="sm" variant="ghost" inset="top bottom" icon-trailing="chevron-down">
                                    <span>{{ number_format($guild->characters_sum_hunter_score) }}</span>
                                </flux:button>
                            </flux:modal.trigger>
                        @else
                            <flux:button size="sm" variant="ghost" inset="top bottom" icon-trailing="chevron-down">
                                <span>0</span>
                            </flux:button>
                        @endif

                        <flux:modal name="guild-hunter-score-{{ $guild->G_Name }}" variant="flyout" position="right">
                            <livewire:pages.guest.rankings.guilds.score-modal
                                :guild="$guild"
                                :type="RankingScoreType::HUNTERS"
                                :wire:key="'guild-hunter-'.$guild->G_Name"
                                lazy
                            />
                        </flux:modal>
                    </flux:cell>

                </flux:row>
            @endforeach
        </flux:rows>
    </flux:table>

    <div>
        <flux:pagination :paginator="$this->guilds" class="!border-0"/>
    </div>
</div>
