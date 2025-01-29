<?php

use App\Enums\Game\AccountLevel;
use App\Enums\Utility\RankingType;
use App\Livewire\Forms\Filters;
use App\Models\Game\Character;
use App\Traits\Searchable;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Modelable;
use Livewire\Attributes\Reactive;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new #[Layout('layouts.guest')] class extends Component {
    use WithPagination, Searchable;

    #[Reactive]
    public RankingType $type;

    #[Reactive]
    public Filters $filters;

    #[Computed]
    public function characters()
    {
        $query = Character::query()
            ->with('guildMember', 'member');

        $query = $this->applySearch($query);
        $query = $this->filters->apply($query);

        return $query->orderBy('ResetCount', 'desc')
            ->selectRaw('*, ROW_NUMBER() OVER (ORDER BY ResetCount DESC) as rank')
            ->simplePaginate(10);
    }

    protected function applySearch($query)
    {
        return $this->searchCharacter($query);
    }
} ?>
<div class="overflow-x-auto relative space-y-8">
    <x-rankings.search wire:model.live.debounce="search"
                       placeholder="Search character..."/>

    <flux:table wire:loading.class="opacity-50">
        <flux:columns>
            @include($this->type->getColumnsPath())
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
                    <flux:row wire:key="{{ $character->Name }}">
                        @include($this->type->getRowsPath(), ['character' => $character])
                    </flux:row>
                @endforeach
            @endif
        </flux:rows>
    </flux:table>

    <div>
        <flux:pagination :paginator="$this->characters" class="!border-0"/>
    </div>
</div>
