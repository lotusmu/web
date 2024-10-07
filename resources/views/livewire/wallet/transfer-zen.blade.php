<?php

use Livewire\Volt\Component;

new class extends Component {
//
}; ?>
<div x-data="{
    source: '',
    destination: '',
    sourceCharacter: '',
    destinationCharacter: '',
    amount: 0
}" class="space-y-6">
    <header>
        <flux:heading size="lg">
            {{ __('Transfer Zen') }}
        </flux:heading>

        <x-flux::subheading>
            {{ __('Move Zen seamlessly between your wallet, and characters.') }}
        </x-flux::subheading>
    </header>

    <div class="flex max-sm:flex-col max-sm:space-y-6">
        <div class="space-y-6 flex-1">
            <flux:radio.group x-model="source" label="{{ __('From (Source)') }}">
                <flux:radio value="wallet" label="{{ __('Zen Wallet') }} (5.02B)"/>
                <flux:radio value="character" label="{{ __('Character') }}"/>
            </flux:radio.group>

            <div x-show="source === 'character'">
                <flux:select variant="listbox" placeholder="{{ __('Select source character') }}"
                             x-model="sourceCharacter">
                    <flux:option>Photograph (543.00M)</flux:option>
                    <flux:option>Design (543.00M)</flux:option>
                    <flux:option>development (543.00M)</flux:option>
                    <flux:option>Accounting (543.00M)</flux:option>
                </flux:select>
            </div>
        </div>

        <div class="mt-8 shrink-0 w-32 max-sm:hidden">
            <flux:icon.chevron-double-right/>
        </div>

        <flux:separator class="sm:hidden"/>

        <div class="space-y-6 flex-1">
            <flux:radio.group x-model="destination" label="{{ __('To (Destination)') }}">
                <template x-if="source !== 'wallet'">
                    <flux:radio value="wallet" label="{{ __('Zen Wallet') }} (5.02B)"/>
                </template>
                <flux:radio value="character" label="{{ __('Character') }}"/>
            </flux:radio.group>

            <div x-show="destination === 'character'">
                <flux:select variant="listbox" placeholder="{{ __('Select destination character') }}"
                             x-model="destinationCharacter">
                    <flux:option>Photography (543.00M)</flux:option>
                    <flux:option>Design services (543.00M)</flux:option>
                    <flux:option>Web development (543.00M)</flux:option>
                    <flux:option>Accounting (543.00M)</flux:option>
                </flux:select>
            </div>

        </div>
    </div>

    <flux:input
        wire:model="amount"
        x-model.number="amount"
        type="number"
        label="{{ __('Amount') }}"
        min="1"
        step="1"
    />

    <flux:button type="submit" variant="primary">
        {{ __('Submit') }}
    </flux:button>
</div>
