<?php

use App\Support\ActivityLog\IdentityProperties;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<div class="space-y-6">
    <header>
        <flux:heading size="lg">
            {{ __('Appearance Settings') }}
        </flux:heading>

        <x-flux::subheading>
            {{ __('Update your appearance settings.') }}
        </x-flux::subheading>
    </header>

    <flux:radio.group x-data variant="segmented" x-model="$flux.appearance">
        <flux:radio value="light" icon="sun">Light</flux:radio>
        <flux:radio value="dark" icon="moon">Dark</flux:radio>
        <flux:radio value="system" icon="computer-desktop">System</flux:radio>
    </flux:radio.group>
</div>
