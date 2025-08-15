<div class="space-y-6">
    <div>
        <flux:heading size="xl" class="text-center">
            {{ __('Forgot your password?')}}
        </flux:heading>

        <flux:subheading class="text-center">
            {{ __('No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </flux:subheading>
    </div>

    <x-auth-card>
        <form wire:submit="sendPasswordResetLink" class="flex flex-col gap-6">
            <flux:input wire:model="email" label="{{ __('Email') }}"/>

            <flux:button variant="primary" type="submit">
                {{ __('Email Password Reset Link') }}
            </flux:button>
        </form>
    </x-auth-card>
</div>
