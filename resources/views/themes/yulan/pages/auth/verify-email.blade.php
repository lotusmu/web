<div class="space-y-6">
    <div>
        <flux:heading size="xl" class="text-center">
            {{__('Thanks for signing up!')}}
        </flux:heading>

        <flux:subheading class="text-center">
            {{__('Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.')}}
        </flux:subheading>
    </div>

    <div class="space-y-1">
        <flux:button variant="primary" wire:click="sendVerification" class="w-full">
            {{ __('Resend Verification Email') }}
        </flux:button>

        <flux:button variant="ghost" wire:click="logout" type="submit" class="w-full">
            {{ __('Log Out') }}
        </flux:button>
    </div>

    <x-info-card color="red" icon="exclamation-triangle" class="!items-start">
        <flux:text>
            {{ __('Unverified accounts will be automatically deleted after 48 hours and cannot access the game until verified.') }}
        </flux:text>
    </x-info-card>
</div>