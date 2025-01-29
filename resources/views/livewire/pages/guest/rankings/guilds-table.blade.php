<?php

use App\Enums\Game\AccountLevel;
use App\Models\Game\Character;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new #[Layout('layouts.guest')] class extends Component {
    use WithPagination;

    #[Computed]
    public function characters()
    {
        return Character::query()
            ->with('guildMember', 'member')
            ->orderBy('ResetCount', 'desc')
            ->selectRaw('*, ROW_NUMBER() OVER (ORDER BY ResetCount DESC) as rank')
            ->paginate(10);
    }
} ?>

<div class="overflow-x-auto">
    <flux:table :paginate="$this->characters" wire:loading.class="opacity-50">
        <flux:columns>
            <flux:column>Guild</flux:column>
            <flux:column>Guild Master</flux:column>
            <flux:column sortable>Members</flux:column>
            <flux:column sortable>Total Resets</flux:column>
            <flux:column sortable>Castle Siege Wins</flux:column>
            <flux:column sortable>King of Yoskreth</flux:column>
            <flux:column sortable>Hunter Score</flux:column>
            <flux:column sortable>Event Score</flux:column>
        </flux:columns>

        <flux:rows>
            @foreach($this->characters as $character)
                <flux:row wire:key="{{ $character->Name }}">

                    <flux:cell class="flex items-center space-x-2">
                        <span>
                            {{ $character->rank }}.
                        </span>

                        <x-guild-identity :guildMember="$character->guildMember"/>
                    </flux:cell>

                    <flux:cell>
                        <flux:link variant="ghost" href="#">
                            {{ $character->Name }}
                        </flux:link>
                    </flux:cell>

                    <flux:cell>
                        {{ rand(10, 30) }}
                    </flux:cell>

                    <flux:cell>
                        {{ rand(100,300) }}
                    </flux:cell>

                    <flux:cell variant="strong">
                        {{ rand(0,3) }}
                    </flux:cell>

                    <flux:cell>
                        {{ rand(0,320) }}
                    </flux:cell>

                    <flux:cell>
                        {{ rand(100,900000) }}
                    </flux:cell>

                    <flux:cell>
                        {{ rand(400,90000) }}
                    </flux:cell>
                </flux:row>
            @endforeach
        </flux:rows>
    </flux:table>
</div>
