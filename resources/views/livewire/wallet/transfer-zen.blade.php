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
            {{ __('Move Zen seamlessly between your wallet, warehouse, and characters.') }}
        </x-flux::subheading>
    </header>

    <div class="flex max-lg:flex-col max-lg:space-y-6">
        <div class="space-y-6 flex-1">
            <flux:radio.group x-model="source" label="{{ __('From (Source)') }}">
                <flux:radio value="wallet" label="{{ __('Zen Wallet') }} (5.02B)"/>
                <flux:radio value="warehouse" label="{{ __('Warehouse') }} (1.34M)"/>
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

        <div class="mt-6 shrink-0 w-32 max-lg:hidden">
            <flux:icon.chevron-double-right/>
        </div>

        <flux:separator class="lg:hidden"/>

        <div class="space-y-6 flex-1">
            <flux:radio.group x-model="destination" label="{{ __('To (Destination)') }}">
                <template x-if="source !== 'wallet'">
                    <flux:radio value="wallet" label="{{ __('Zen Wallet') }} (5.02B)"/>
                </template>
                <template x-if="source !== 'warehouse'">
                    <flux:radio value="warehouse" label="{{ __('Warehouse') }} (1.34M)"/>
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
        type="number"
        label="{{ __('Amount of Zen to transfer') }}"
        x-model.number="amount"
        min="1"
        step="1"
    />

    <flux:button type="submit" variant="primary">
        {{ __('Submit') }}
    </flux:button>
</div>
