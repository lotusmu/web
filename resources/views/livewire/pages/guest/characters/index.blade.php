<?php

use App\Enums\Game\CharacterClass;
use App\Models\Game\Character;
use App\Models\Game\Guild;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {

    public ?string $name = null;

    public function mount(?string $name = null): void
    {
        $this->name = $name;
    }

    #[Computed]
    public function character(): ?Character
    {
        return Character::with([
            'member',
            'member.status',
            'guildMember.guild',
            'quest'
        ])
            ->where('Name', $this->name)
            ->first();
    }
}; ?>

<flux:main container>
    <flux:card class="max-w-2xl mx-auto space-y-8">
        @if($this->character)
            <div>
                <flux:heading size="lg" class="mb-2">
                    {{ __('General Information') }}
                </flux:heading>

                <flux:separator class="mb-8"/>

                <div class="flex items-start justify-start space-x-8">
                    <div class="min-w-64">
                        <img src="{{ asset($this->character->Class->getBigImage() ) }}" alt="Character Class Image"
                             class="w-64 h-64 object-cover">
                    </div>

                    <x-character.details :character="$this->character"/>
                </div>
            </div>

            <div>
                <flux:heading size="lg" class="mb-2">
                    {{ __('Account Information') }}
                </flux:heading>

                <flux:separator class="mb-8"/>

                <div class="flex items-center justify-evenly mb-8">
                    <div class="w-full">
                        <flux:subheading>{{ __('Last Login') }}</flux:subheading>
                        <flux:heading>
                            {{ $this->character->member->status?->lastLogin ?? __('Never')}}
                        </flux:heading>
                    </div>
                    <div class="w-full">
                        <flux:subheading>{{ __('Last Disconnect') }}</flux:subheading>
                        <flux:heading>
                            {{ $this->character->member->status?->lastDisconnect ?? __('Never') }}
                        </flux:heading>
                    </div>
                    <div class="w-full">
                        <flux:subheading class="mb-2">{{ __('Current Status') }}</flux:subheading>
                        <flux:badge size="sm"
                                    color="{{ $this->character->member->status?->currentStatus ? 'emerald' : 'rose' }}">
                            {{ $this->character->member->status?->currentStatus ?? __('Offline') }}
                        </flux:badge>
                    </div>
                </div>

                <flux:table>
                    <flux:columns>
                        <flux:column>{{ __('Name') }}</flux:column>
                        <flux:column>{{ __('Class') }}</flux:column>
                        <flux:column>{{ __('Level') }}</flux:column>
                        <flux:column>{{ __('Resets') }}</flux:column>
                        <flux:column>{{ __('Guild') }}</flux:column>
                    </flux:columns>

                    <flux:rows>
                        @foreach($this->character->member->characters as $char)
                            <x-rankings.table.cells.character-name :character=" $char
                        "/>

                            <x-rankings.table.cells.character-class :character="$char"/>

                            <flux:cell>{{ $char->cLevel }}</flux:cell>

                            <flux:cell>{{ $char->ResetCount }}</flux:cell>

                            <flux:cell>
                                <x-guild-identity :guild-member="$char->guildMember"/>
                            </flux:cell>
                        @endforeach
                    </flux:rows>
                </flux:table>
            </div>
        @else
            <flux:text>
                Character not found or has been deleted.
            </flux:text>
        @endif
    </flux:card>
</flux:main>
