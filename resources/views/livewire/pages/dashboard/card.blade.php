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
    public function accountLevel(): ?array
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

<flux:card class="flex max-sm:flex-col justify-evenly max-sm:gap-4 gap-2 text-center">
    <div class="flex-1 min-w-0">
        <flux:subheading>
            {{__('Tokens')}}
        </flux:subheading>
        <flux:heading size="xl">
            {{ $this->resources->tokens }}
        </flux:heading>
    </div>

    <flux:separator vertical variant="subtle" class="sm:block hidden -my-6"/>
    <flux:separator variant="subtle" class="max-sm:block hidden"/>

    <div class="flex-1 min-w-0">
        <flux:subheading>
            {{__('Credits')}}
        </flux:subheading>
        <flux:heading size="xl">
            {{ $this->resources->credits }}
        </flux:heading>
    </div>

    <flux:separator vertical variant="subtle" class="sm:block hidden -my-6"/>
    <flux:separator variant="subtle" class="max-sm:block hidden"/>

    <div class="flex-1 min-w-0">
        <flux:subheading>
            {{__('Zen')}}
        </flux:subheading>
        <flux:heading size="xl">
            {{ $this->resources->zen }}
        </flux:heading>
    </div>

    <flux:separator vertical variant="subtle" class="sm:block hidden -my-6"/>
    <flux:separator variant="subtle" class="max-sm:block hidden"/>

    <div class="flex-1 min-w-0">
        <flux:subheading>
            {{__('Account Level')}}
        </flux:subheading>

        @if ($this->accountLevel)
            <flux:badge icon="fire" size="lg" color="{{ $this->accountLevel['color'] }}" class="mt-2">
                {{ $this->accountLevel['label'] }}
            </flux:badge>
        @else
            <flux:heading size="xl">
                {{__('Regular')}}
            </flux:heading>
        @endif
    </div>
</flux:card>

