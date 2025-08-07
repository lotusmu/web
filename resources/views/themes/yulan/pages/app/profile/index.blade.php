<div>
    <header>
        <flux:heading size="xl">
            {{ __('Profile Settings') }}
        </flux:heading>

        <x-flux::subheading>
            {{ __('Manage your email and password.') }}
        </x-flux::subheading>
    </header>

    <flux:tab.group variant="flush" class="mt-8">
        <flux:tabs wire:model="tab">
            <flux:tab name="email" icon="envelope">{{__('Email')}}</flux:tab>
            <flux:tab name="password" icon="lock-closed">{{__('Password')}}</flux:tab>
            <flux:tab name="appearance" icon="swatch">{{__('Appearance')}}</flux:tab>
        </flux:tabs>

        <flux:tab.panel name="email">
            <livewire:pages.app.profile.profile-email/>
        </flux:tab.panel>
        <flux:tab.panel name="password">
            <livewire:pages.app.profile.profile-password/>
        </flux:tab.panel>
        <flux:tab.panel name="appearance">
            <livewire:pages.app.profile.appearance/>
        </flux:tab.panel>
    </flux:tab-group>
</div>
