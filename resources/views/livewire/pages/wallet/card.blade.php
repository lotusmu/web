<?php

use App\Models\User\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {
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

    #[On('resourcesUpdated')]
    public function onResourcesUpdated(): void
    {
        $this->user->refresh();
    }
} ?>

<div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
    <flux:card class="max-sm:col-span-2 !p-4">
        <flux:subheading size="sm">
            {{__('Tokens')}}
        </flux:subheading>
        <flux:heading size="lg">
            {{ $this->resources->tokens }}
        </flux:heading>
    </flux:card>

    <flux:card class="!p-4">
        <flux:subheading size="sm">
            {{__('Credits')}}
        </flux:subheading>
        <flux:heading size="lg">
            {{ $this->resources->credits }}
        </flux:heading>
    </flux:card>

    <flux:card class="!p-4">
        <flux:subheading size="sm">
            {{__('Zen')}}
        </flux:subheading>
        <flux:heading size="lg">
            {{ $this->resources->zen }}
        </flux:heading>
    </flux:card>
</div>
