<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.auth')] class extends Component {
    public string $password = '';

    /**
     * Confirm the current user's password.
     */
    public function confirmPassword(): void
    {
        $this->validate([
            'password' => ['required', 'string'],
        ]);

        if ( ! Auth::guard('web')->validate([
            'email'    => Auth::user()->email,
            'password' => $this->password,
        ])
        ) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        session(['auth.password_confirmed_at' => time()]);

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="space-y-6">
    <div>
        <flux:heading size="xl" class="text-center">
            {{__('Hold on')}}
        </flux:heading>

        <flux:subheading class="text-center">
            {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
        </flux:subheading>
    </div>

    <form wire:submit="confirmPassword" class="flex flex-col gap-6">
        <flux:input viewable wire:model="password" type="password" label="{{__('Password')}}"/>

        <flux:button variant="primary" type="submit">
            {{ __('Confirm') }}
        </flux:button>
    </form>
</div>
