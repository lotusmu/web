<?php

use App\Enums\Game\AccountLevel;
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
            <flux:column>Name</flux:column>
            <flux:column>Class</flux:column>
            <flux:column>Level</flux:column>
            <flux:column>Resets</flux:column>
            <flux:column>HoF</flux:column>
            <flux:column>Quests</flux:column>
            <flux:column>Guild</flux:column>
            <flux:column>Location</flux:column>
        </flux:columns>

        <flux:rows>
            @foreach($this->characters as $character)
                <flux:row wire:key="{{ $character->Name }}">

                    <flux:cell class="flex items-center space-x-2">
                            <span>
                                {{ $character->rank }}.
                            </span>

                        <flux:link variant="ghost" href="#">
                            {{ $character->Name }}
                        </flux:link>

                        @if($character->member->AccountLevel !== AccountLevel::Regular)
                            <flux:icon.fire variant="mini"
                                            class="text-{{ $character->member->AccountLevel->badgeColor() }}-500"/>
                        @endif
                    </flux:cell>

                    <flux:cell>
                        <div class="flex items-center gap-3">
                            <flux:avatar size="xs"
                                         src="{{ asset($character->Class->getImagePath()) }}"/>

                            <span class="max-sm:hidden">
                                            {{ $character->Class->getLabel()  }}
                                        </span>
                        </div>
                    </flux:cell>

                    <flux:cell>
                        {{ $character->cLevel }}
                    </flux:cell>

                    <flux:cell>
                        {{ $character->ResetCount }}
                    </flux:cell>

                    <flux:cell>
                        {{ rand(0,5) }}
                    </flux:cell>

                    <flux:cell>
                        {{ rand(0,320) }}
                    </flux:cell>

                    <flux:cell>
                        <x-guild-identity :guildMember="$character->guildMember"/>
                    </flux:cell>

                    <flux:cell>
                        {{ $character->MapNumber->getLabel() }}
                    </flux:cell>
                </flux:row>
            @endforeach
        </flux:rows>
    </flux:table>
</div>
