<?php

use App\Models\Game\Character;
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
            ->select('Name', 'cLevel', 'ResetCount', 'Class')
            ->where('AccountID', auth()->user()->name)
            ->tap(fn($query) => $this->sortBy ? $query->orderBy($this->sortBy, $this->sortDirection) : $query)
            ->get();
    }
} ?>

<flux:card>
    <flux:heading size="lg">
        Characters
    </flux:heading>

    <flux:subheading>
        Here are all your lovely little characters.
    </flux:subheading>

    <flux:table class="mt-6">
        <flux:columns>
            <flux:column>Character</flux:column>
            <flux:column>Class</flux:column>
            <flux:column>Level</flux:column>
            <flux:column sortable :sorted="$sortBy === 'ResetCount'" :direction="$sortDirection"
                         wire:click="sort('ResetCount')">Resets
            </flux:column>
        </flux:columns>

        <flux:rows>
            @foreach ($this->characters as $character)
                <flux:row :key="$character->Name">
                    <flux:cell>{{ $character->Name }}</flux:cell>
                    <flux:cell class="flex items-center gap-3">
                        <flux:avatar size="xs" src="{{ asset($character->Class->getImagePath()) }}"/>

                        <span class="max-lg:hidden">
                        {{  $character->Class->getLabel()  }}
                        </span>
                    </flux:cell>
                    <flux:cell>{{ $character->cLevel }}</flux:cell>
                    <flux:cell>{{ $character->ResetCount }}</flux:cell>
                </flux:row>
            @endforeach
        </flux:rows>
    </flux:table>
</flux:card>
