<div class="space-y-6">
    <flux:heading size="xl" class="text-center">
        {{__('Reset your password')}}
    </flux:heading>

    <form wire:submit="resetPassword" class="flex flex-col gap-6">
        <flux:input wire:model="email" label="{{__('Email')}}"/>
        <flux:input viewable wire:model="password" type="password" label="{{__('Password')}}"/>
        <flux:input viewable wire:model="password_confirmation" type="password" label="{{__('Confirm Password')}}"/>

        <flux:button variant="primary" type="submit">
            {{ __('Reset Password') }}
        </flux:button>
    </form>
</div>