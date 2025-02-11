<?php

use App\Enums\Game\AccountLevel;
use App\Enums\Game\CharacterClass;
use App\Models\Game\Character;
use App\Models\Game\Guild;
use Illuminate\Support\Collection;
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

    #[Computed]
    public function otherCharacters()
    {
        return Character::with([
            'guildMember.guild',
        ])
            ->where('AccountID', $this->character->AccountID)
            ->where('Name', '!=', $this->name)
            ->get();
    }


    #[Computed]
    public function accountLevel(): ?array
    {
        $level = $this->character->member->AccountLevel;

        if ($level === AccountLevel::Regular) {
            return null;
        }

        return [
            'label' => $this->character->member->AccountLevel->getLabel(),
            'color' => $this->character->member->AccountLevel->badgeColor(),
        ];
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
                        <img src="{{ asset($this->character->Class->getBigImage()) }}" alt="Character Class Image"
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
                        <flux:subheading class="mb-2">{{ __('Account Level') }}</flux:subheading>
                        @if ($this->accountLevel)
                            <flux:badge icon="fire" size="sm" color="{{ $this->accountLevel['color'] }}">
                                {{ $this->accountLevel['label'] }}
                            </flux:badge>
                        @else
                            <flux:heading>
                                {{__('Regular')}}
                            </flux:heading>
                        @endif
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
                        @foreach($this->otherCharacters as $character)
                            <flux:row :key="$character->Name">
                                <flux:cell>
                                    <flux:link variant="ghost"
                                               :href="route('character', ['name' => $character->Name])"
                                               wire:navigate>
                                        {{ $character->Name }}
                                    </flux:link>
                                </flux:cell>

                                <x-rankings.table.cells.character-class :character="$character"/>

                                <flux:cell>
                                    {{ $character->cLevel }}
                                </flux:cell>

                                <flux:cell>
                                    {{ $character->ResetCount }}
                                </flux:cell>

                                <flux:cell>
                                    <x-guild-identity :guild-member="$character->guildMember"/>
                                </flux:cell>
                            </flux:row>
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
