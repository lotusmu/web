<div class="space-y-6">
    <div>
        <flux:heading size="xl" class="text-center">
            {{__('Hold on')}}
        </flux:heading>

        <flux:subheading class="text-center">
            {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
        </flux:subheading>
    </div>

    <x-auth-card>
        <form wire:submit="confirmPassword" class="flex flex-col gap-6">
            <flux:input viewable wire:model="password" type="password" label="{{__('Password')}}"/>

            <flux:button variant="primary" type="submit">
                {{ __('Confirm') }}
            </flux:button>
        </form>
    </x-auth-card>
</div>
