<?php

use App\Models\User\User;
use Livewire\Attributes\Computed;
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
} ?>

<div class="grid sm:grid-cols-3 gap-6 max-sm:text-center max-sm:gap-2">
    <flux:card>
        <flux:subheading>
            {{__('Tokens')}}
        </flux:subheading>
        <flux:heading size="xl">
            {{ $this->resources->tokens }}
        </flux:heading>
    </flux:card>

    <flux:card>
        <flux:subheading>
            {{__('Credits')}}
        </flux:subheading>
        <flux:heading size="xl">
            {{ $this->resources->credits }}
        </flux:heading>
    </flux:card>

    <flux:card>
        <flux:subheading>
            {{__('Zen')}}
        </flux:subheading>
        <flux:heading size="xl">
            {{ $this->resources->zen }}
        </flux:heading>
    </flux:card>
</div>
