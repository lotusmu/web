<?php

use App\Enums\Utility\RankingPeriodType;
use App\Enums\Utility\RankingScoreType;
use App\Livewire\Forms\Filters;
use App\Models\Game\Character;
use App\Traits\Searchable;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Reactive;
use Livewire\Volt\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

new #[Layout('layouts.guest')] class extends Component {
    use WithPagination, WithoutUrlPagination, Searchable;

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
                'MapNumber'
            ])
            ->with([
                'member:memb___id,AccountLevel',
                'guildMember.guild',
                'hunterScores'       => function ($q) {
                    $q->select([
                        'Name',
                        'MonsterName',
                        'MonsterClass',
                        'KillCount',
                        'PointsPerKill',
                        'TotalPoints'
                    ])
                        ->with(['monster:MonsterName,MonsterClass,image_path']);
                },
                'weeklyHunterScores' => function ($q) {
                    $q->select([
                        'Name',
                        'MonsterName',
                        'MonsterClass',
                        'KillCount',
                        'PointsPerKill',
                        'TotalPoints'
                    ])
                        ->with(['monster:MonsterName,MonsterClass,image_path']);
                }
            ]);

        $query = $this->applySearch($query);
        $query = $this->filters->apply($query);

        return $query->simplePaginate(10);
    }

    protected function applySearch($query)
    {
        return $this->searchCharacter($query);
    }

    protected function getRowKey($character): string
    {
        return "{$character->Name}.-weekly-row";
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
            @include('components.rankings.table.columns.weekly')
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
                        @include('components.rankings.table.rows.weekly')
                    </flux:row>
                @endforeach
            @endif
        </flux:rows>
    </flux:table>

    <div>
        <flux:pagination :paginator="$this->characters" class="!border-0"/>
    </div>
</div>
