<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {
    #[\Livewire\Attributes\Url]
    public string $tab = 'email';
}; ?>

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
            <flux:tab name="email" icon="envelope">Email</flux:tab>
            <flux:tab name="password" icon="lock-closed">Password</flux:tab>
            <flux:tab name="appearance" icon="swatch">Appearance</flux:tab>
        </flux:tabs>

        <flux:tab.panel name="email">
            <livewire:pages.profile.email/>
        </flux:tab.panel>
        <flux:tab.panel name="password">
            <livewire:pages.profile.password/>
        </flux:tab.panel>
        <flux:tab.panel name="appearance">
            <livewire:pages.profile.appearance/>
        </flux:tab.panel>
    </flux:tab-group>
</div>
