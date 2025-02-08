<?php

use App\Enums\Utility\RankingPeriodType;
use App\Enums\Utility\RankingScoreType;
use App\Livewire\Forms\Filters;
use App\Models\Game\Character;
use App\Enums\Utility\ResourceType;
use App\Models\Game\Ranking\WeeklyRankingReward;
use App\Traits\Searchable;
use App\Traits\Sortable;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Reactive;
use Livewire\Volt\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

new #[Layout('layouts.guest')] class extends Component {
    use WithPagination;
    use WithoutUrlPagination;
    use Searchable;
    use Sortable;

    public function mount()
    {
        $this->sortBy = 'weekly-event-score';
    }

    #[Reactive]
    public Filters $filters;

    #[Computed]
    public function characters()
    {
        $query = Character::query()
            ->select([
                'Name',
                'AccountID',
                'cLevel',
                'ResetCount',
                'Class',
                'MapNumber',
                'HunterScoreWeekly',
                'EventScoreWeekly',
            ])
            ->with([
                'member:memb___id,AccountLevel',
                'guildMember.guild',
            ]);

        $query = $this->applySearch($query);
        $query = $this->filters->apply($query);
        $query = $this->applySorting($query);

        return $query->simplePaginate(10);
    }

    #[Computed]
    public function rankingRewards()
    {
        return WeeklyRankingReward::query()
            ->orderBy('position_from')
            ->get();
    }

    protected function getRewardsForPosition(int $iteration): array
    {
        // Calculate actual position based on pagination
        $position = ($this->characters->currentPage() - 1) * 10 + $iteration;

        return $this->rankingRewards()
            ->first(fn($reward) => $position >= $reward->position_from &&
                $position <= $reward->position_to
            )?->rewards ?? [];
    }

    protected function applySearch($query)
    {
        return $this->searchCharacter($query);
    }

    protected function applySorting($query)
    {
        return $this->sortCharacters($query);
    }

    protected function getRowKey($character): string
    {
        return "{$character->Name}-weekly-row";
    }

    protected function getScoreKey($character, RankingScoreType $type): string
    {
        return $character->Name.'-'.RankingPeriodType::WEEKLY->value.'-'.$type->value.'-score';
    }

    public function placeholder()
    {
        return view('livewire.pages.guest.rankings.placeholders.table');
    }
} ?>

<div class="overflow-x-auto relative space-y-8">
    <x-rankings.search wire:model.live.debounce="search"/>

    <flux:table wire:loading.class="opacity-50">
        <flux:columns>
            <flux:column>
                #
            </flux:column>

            <flux:column>
                {{ __('Character') }}
            </flux:column>

            <flux:column>
                {{ __('Class') }}
            </flux:column>

            <flux:column>
                {{ __('Level') }}
            </flux:column>

            <flux:column>
                {{ __('Resets') }}
            </flux:column>

            <flux:column>
                {{ __('Guild') }}
            </flux:column>

            <flux:column>
                <flux:table.sortable
                    wire:click="sort('weekly-event-score')"
                    :sorted="$sortBy === 'weekly-event-score'"
                    :direction="$sortDirection"
                    class="flex items-center gap-2">
                    <span>{{ __('Weekly Event Score') }}</span>
                </flux:table.sortable>

                <x-rankings.scoring-rules-trigger :score-type="RankingScoreType::EVENTS"/>
            </flux:column>

            <flux:column>
                <flux:table.sortable
                    wire:click="sort('weekly-hunt-score')"
                    :sorted="$sortBy === 'weekly-hunt-score'"
                    :direction="$sortDirection"
                    class="flex items-center gap-2">
                    <span>{{ __('Weekly Hunt Score') }}</span>
                </flux:table.sortable>

                <x-rankings.scoring-rules-trigger :score-type="RankingScoreType::HUNTERS"/>
            </flux:column>

            <flux:column>
                <span class="mr-1">{{ __('Reward') }}</span>

                <flux:modal.trigger name="rewards-distribution">
                    <flux:button variant="ghost" icon="information-circle" size="sm" inset="top bottom"/>
                </flux:modal.trigger>

                <flux:separator vertical class="mx-1"/>

                <flux:button variant="ghost"
                             icon="history"
                             size="sm"
                             inset="top bottom"
                             href="{{ route('rankings.archive') }}"
                             wire:navigate.hover>
                    {{ __('Archive') }}
                </flux:button>
            </flux:column>
        </flux:columns>

        <flux:rows>
            @if($this->characters->isEmpty())
                <flux:row>
                    <flux:cell colspan="100%">
                        {{ __('No characters found.') }}
                    </flux:cell>
                </flux:row>
            @else
                @foreach($this->characters as $character)
                    <flux:row wire:key="{{ $this->getRowKey($character) }}">
                        <x-rankings.table.cells.rank :paginator="$this->characters" :$loop/>

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

                        <x-rankings.table.cells.score
                            :character="$character"
                            :score-type="RankingScoreType::EVENTS"
                            :scope="RankingPeriodType::WEEKLY"
                            :score="$character->EventScoreWeekly"
                            :modal-key="$this->getScoreKey($character, RankingScoreType::EVENTS)"
                        />

                        <x-rankings.table.cells.score
                            :character="$character"
                            :score-type="RankingScoreType::HUNTERS"
                            :scope="RankingPeriodType::WEEKLY"
                            :score="$character->HunterScoreWeekly"
                            :modal-key="$this->getScoreKey($character, RankingScoreType::HUNTERS)"
                        />

                        <flux:cell class="space-x-1">
                            @php $rewards = $this->getRewardsForPosition($loop->iteration) @endphp

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
    </flux:table>

    <div>
        <flux:pagination :paginator="$this->characters" class="!border-0"/>
    </div>

    <flux:modal name="rewards-distribution" class="w-96 space-y-6">
        <header>
            <flux:heading size="lg">
                {{ __('Weekly Rankings Rewards') }}
            </flux:heading>
            <flux:subheading>
                {{ __('Rewards are distributed every Sunday at 23:59 server time.') }}
            </flux:subheading>
        </header>

        <flux:table>
            <flux:columns>
                <flux:column>
                    {{ __('Rank') }}
                </flux:column>

                <flux:column>
                    {{ __('Reward') }}
                </flux:column>
            </flux:columns>

            <flux:rows>
                @foreach (range(1, 6) as $i)
                    <flux:row>
                        <flux:cell>
                            # 1-5
                        </flux:cell>

                        <flux:cell class="space-x-1">
                            <x-resource-badge value="100000000"
                                              :resource="ResourceType::ZEN"/>

                            <x-resource-badge value="1000"
                                              :resource="ResourceType::CREDITS"/>
                        </flux:cell>
                    </flux:row>
                @endforeach
            </flux:rows>
        </flux:table>

        <div class="flex items-center justify-center gap-1">
            <flux:icon.information-circle variant="mini" inset="top bottom"/>

            <flux:text size="sm">
                {{ __('Rankings reset immediately after rewards distribution.') }}
            </flux:text>
        </div>
    </flux:modal>
</div>
