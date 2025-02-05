<?php

use App\Enums\Utility\RankingPeriodType;
use App\Enums\Utility\RankingScoreType;
use App\Livewire\Forms\Filters;
use App\Models\Game\Character;
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
                'HofWins',
                'Class',
                'MapNumber',
                'HunterScore',
                'EventScore',
            ])
            ->with([
                'member:memb___id,AccountLevel',
                'guildMember.guild',
                'quest:Name,Quest',
            ]);

        $query = $this->applySearch($query);
        $query = $this->filters->apply($query);
        $query = $this->applySorting($query);

        return $query->simplePaginate(10);
    }

    protected function applySearch($query)
    {
        return $this->searchCharacter($query);
    }

    protected function getRowKey($character): string
    {
        return $character->Name.'-general-row';
    }

    protected function getScoreKey($character, RankingScoreType $type): string
    {
        return $character->Name.'-'.RankingPeriodType::TOTAL->value.'-'.$type->value.'-score';
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
                {{ __('Character') }}
            </flux:column>

            <flux:column>
                {{ __('Class') }}
            </flux:column>

            <flux:column>
                {{ __('Level') }}
            </flux:column>

            <flux:column sortable :sorted="$sortBy === 'resets'" :direction="$sortDirection"
                         wire:click="sort('resets')">
                {{ __('Resets') }}
            </flux:column>

            <flux:column sortable :sorted="$sortBy === 'hof'" :direction="$sortDirection"
                         wire:click="sort('hof')">
                {{ __('HoF') }}
            </flux:column>

            <flux:column sortable :sorted="$sortBy === 'quests'" :direction="$sortDirection"
                         wire:click="sort('quests')">
                {{ __('Quests') }}
            </flux:column>

            <flux:column>
                {{ __('Guild') }}
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

            <flux:column>
                {{ __('Location') }}
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
                        <x-rankings.table.cells.character-name :$character/>

                        <x-rankings.table.cells.character-class :$character/>

                        <flux:cell>
                            {{ $character->cLevel }}
                        </flux:cell>

                        <flux:cell>
                            {{ $character->ResetCount }}
                        </flux:cell>

                        <flux:cell>
                            {{ $character->HofWins }}
                        </flux:cell>

                        <flux:cell>
                            {{ $character->quest_count }}
                        </flux:cell>

                        <flux:cell>
                            <x-guild-identity :guildMember="$character->guildMember"/>
                        </flux:cell>

                        <x-rankings.table.cells.score
                            :character="$character"
                            :score-type="RankingScoreType::EVENTS"
                            :scope="RankingPeriodType::TOTAL"
                            :score="$character->EventScore"
                            :modal-key="$this->getScoreKey($character, RankingScoreType::EVENTS)"
                        />

                        <x-rankings.table.cells.score
                            :character="$character"
                            :score-type="RankingScoreType::HUNTERS"
                            :scope="RankingPeriodType::TOTAL"
                            :score="$character->HunterScore"
                            :modal-key="$this->getScoreKey($character, RankingScoreType::HUNTERS)"
                        />

                        <flux:cell>
                            {{ $character->MapNumber->getLabel() }}
                        </flux:cell>
                    </flux:row>
                @endforeach
            @endif
        </flux:rows>
    </flux:table>

    <div>
        <flux:pagination :paginator="$this->characters" class="!border-0"/>
    </div>
</div>
