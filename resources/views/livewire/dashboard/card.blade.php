<?php

use App\Enums\Game\AccountLevel;
use App\Models\Game\Character;
use App\Models\User\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use App\Enums\Game\CharacterClass;

new #[Layout('layouts.app')] class extends Component {
    public User $user;

    public function mount(): void
    {
        $this->user = auth()->user();
    }


    #[Computed]
    public function resources(): object
    {
        return (object) [
            'tokens'  => $this->user->tokens->format(),
            'credits' => $this->user->credits->format(),
            'zen'     => $this->user->zen->format(),
        ];
    }

    #[Computed]
    public function accountLevel()
    {
        $level = $this->user->member->AccountLevel;
        if ($level === AccountLevel::Regular) {
            return null;
        }

        return [
            'label' => $this->user->member->AccountLevel->getLabel(),
            'color' => $this->user->member->AccountLevel->badgeColor(),
        ];
    }
} ?>

<flux:card class="flex max-sm:flex-col justify-evenly gap-2 text-center">
    <div>
        <flux:subheading>
            Tokens
        </flux:subheading>
        <flux:heading size="xl">
            {{ $this->resources->tokens }}
        </flux:heading>
    </div>

    <flux:separator vertical variant="subtle" class="sm:block hidden"/>
    <flux:separator variant="subtle" class="max-sm:block hidden"/>

    <div>
        <flux:subheading>
            Credits
        </flux:subheading>
        <flux:heading size="xl">
            {{ $this->resources->credits }}
        </flux:heading>
    </div>

    <flux:separator vertical variant="subtle" class="sm:block hidden"/>
    <flux:separator variant="subtle" class="max-sm:block hidden"/>

    <div>
        <flux:subheading>
            Zen
        </flux:subheading>
        <flux:heading size="xl">
            {{ $this->resources->zen }}
        </flux:heading>
    </div>

    <flux:separator vertical variant="subtle" class="sm:block hidden"/>
    <flux:separator variant="subtle" class="max-sm:block hidden"/>

    <div>
        <flux:subheading>
            Account Level
        </flux:subheading>

        @if ($this->accountLevel)
            <flux:badge icon="fire" size="lg" color="{{ $this->accountLevel['color'] }}" class="mt-2">
                {{ $this->accountLevel['label'] }}
            </flux:badge>
        @else
            <flux:heading size="xl">
                Regular
            </flux:heading>
        @endif
    </div>
</flux:card>

