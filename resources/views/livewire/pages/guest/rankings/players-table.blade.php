<?php

use App\Enums\Game\AccountLevel;
use App\Enums\Utility\RankingType;
use App\Livewire\Forms\Filters;
use App\Models\Game\Character;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Modelable;
use Livewire\Attributes\Reactive;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new #[Layout('layouts.guest')] class extends Component {
    use WithPagination;

    #[Reactive]
    public RankingType $type;

    #[Reactive]
    public Filters $filters;

    #[Computed]
    public function characters()
    {
        $query = Character::query()
            ->with('guildMember', 'member');

        $query = $this->filters->apply($query);

        return $query->orderBy('ResetCount', 'desc')
            ->selectRaw('*, ROW_NUMBER() OVER (ORDER BY ResetCount DESC) as rank')
            ->simplePaginate(10);
    }
} ?>
<div class="overflow-x-auto relative">
    <flux:table :paginate="$this->characters" wire:loading.class="opacity-50">
        <flux:columns>
            @include($this->type->getColumnsPath())
        </flux:columns>

        <flux:rows>
            @foreach($this->characters as $character)
                <flux:row wire:key="{{ $character->Name }}">
                    @include($this->type->getRowsPath(), ['character' => $character])
                </flux:row>
            @endforeach
        </flux:rows>
    </flux:table>
</div>
