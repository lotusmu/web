<div>
    <header>
        <flux:heading size="lg">
            {{ __('Account details') }}
        </flux:heading>

        <flux:subheading>
            {{ __("Update your account's profile email address.") }}
        </flux:subheading>
    </header>

    <form wire:submit="updateProfileInformation" class="mt-6 space-y-6">
        <flux:input wire:model="email" label="{{__('Email')}}"/>

        @if (auth()->user() instanceof MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
            <flux:card class="space-y-8">
                <flux:text class="text-sm mt-2 text-gray-800">
                    {{ __('Your email address is unverified.') }}
                </flux:text>

                <flux:button wire:click.prevent="sendVerification" variant="primary">
                    {{ __('Click here to re-send the verification email.') }}
                </flux:button>
            </flux:card>
        @endif

        <flux:button type="submit" variant="primary">
            {{ __('Save') }}
        </flux:button>
    </form>
</div>