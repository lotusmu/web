<div>
    <header>
        <flux:heading size="lg">
            {{ __('Update Password') }}
        </flux:heading>

        <x-flux::subheading>
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </x-flux::subheading>
    </header>

    <form wire:submit="updatePassword" class="mt-6 space-y-6">
        <flux:input viewable type="password" wire:model="current_password" label="{{__('Current Password')}}"/>
        <flux:input viewable type="password" wire:model="password" label="{{__('New Password')}}"/>
        <flux:input viewable type="password" wire:model="password_confirmation" label="{{__('Confirm Password')}}"/>


        <flux:button type="submit" variant="primary">
            {{ __('Save') }}
        </flux:button>
    </form>
</div>