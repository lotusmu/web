<?php

use App\Models\User\User;
use Livewire\Volt\Component;

new class extends Component {
    public User $user;
    public int $amount;

    public function mount()
    {
        $this->user = auth()->user();
    }

    public function exchange()
    {
        $this->validate([
            'amount' => 'required|integer|min:1',
        ]);

        $wallet = $this->user->member->wallet;

        if ($this->user->member->tokens < $this->amount) {
            Flux::toast('Insufficient tokens', 'error');

            return;
        }

        $this->user->member->tokens -= $this->amount;
        $wallet->credits            += $this->amount;
        $wallet->save();
        $this->user->member->save();

        Flux::toast('Credits updated successfully', 'success');

        $this->amount = 0;
    }
}; ?>

<div>
    <header>
        <flux:heading size="lg">
            {{ __('Exchange Tokens') }}
        </flux:heading>

        <x-flux::subheading>
            {{ __('Exchange your tokens for credits at a 1:1 ratio.') }}
        </x-flux::subheading>
    </header>

    <form wire:submit="exchange" class="mt-6 space-y-6">
        <flux:input
            type="number"
            label="{{ __('Amount') }}"
            wire:model="amount"
            min="0"
            step="1"
        />
        <flux:button type="submit" variant="primary">
            {{ __('Submit') }}
        </flux:button>
    </form>
</div>
