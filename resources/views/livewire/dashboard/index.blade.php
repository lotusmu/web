<?php

use App\Models\Game\Character;
use App\Models\User\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use App\Enums\Game\CharacterClass;

new #[Layout('layouts.app')] class extends Component {
    public $sortBy = 'ResetCount';
    public $sortDirection = 'desc';

    public function sort($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy        = $column;
            $this->sortDirection = 'asc';
        }
    }

    #[Computed]
    public function characters()
    {
        return Character::query()
            ->select('Name', 'cLevel', 'ResetCount', 'Class', 'PkCount')
            ->where('AccountID', auth()->user()->name)
            ->tap(fn($query) => $this->sortBy ? $query->orderBy($this->sortBy, $this->sortDirection) : $query)
            ->get();
    }
} ?>

<div class="space-y-6">
    <livewire:dashboard.card/>

    <flux:table>
        <flux:columns>
            <flux:column>Character</flux:column>
            <flux:column>Class</flux:column>
            <flux:column>Kills</flux:column>
            <flux:column>Level</flux:column>
            <flux:column sortable :sorted="$sortBy === 'ResetCount'" :direction="$sortDirection"
                         wire:click="sort('ResetCount')">Resets
            </flux:column>
        </flux:columns>

        <flux:rows>
            @foreach ($this->characters as $character)
                <livewire:dashboard.character-row :$character wire:key="{{ $character->Name }}"/>
            @endforeach
        </flux:rows>
    </flux:table>
</div>
