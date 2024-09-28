<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.auth')] class extends Component {
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));

            return;
        }

        $this->reset('email');

        Flux::toast(__($status));
    }
}; ?>

<div class="space-y-6">
    <div>
        <flux:heading size="xl" class="text-center">
            {{ __('Forgot your password?')}}
        </flux:heading>

        <flux:subheading class="text-center">
            {{ __('No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </flux:subheading>
    </div>

    <form wire:submit="sendPasswordResetLink" class="flex flex-col gap-6">
        <flux:input wire:model="email" label="{{ __('Email') }}"/>

        <flux:button variant="primary" type="submit">
            {{ __('Email Password Reset Link') }}
        </flux:button>
    </form>
</div>
