<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {
    #[\Livewire\Attributes\Url]
    public string $tab = 'send-gift';
}; ?>

<div class="space-y-8">
    <livewire:pages.wallet.card/>

    <flux:tab.group variant="flush">
        <flux:tabs wire:model="tab" class="max-sm:hidden">
            <flux:tab name="send-gift" icon="gift">{{ __('Send Gift') }}</flux:tab>
            <flux:tab name="exchange-tokens" icon="arrows-right-left">{{ __('Exchange Tokens') }}</flux:tab>
            <flux:tab name="transfer-zen" icon="banknotes">{{ __('Transfer Zen') }}</flux:tab>
        </flux:tabs>

        <flux:tabs wire:model="tab" variant="segmented" size="sm" class="sm:hidden mx-auto w-full">
            <flux:tab name="send-gift">{{ __('Send Gift') }}</flux:tab>
            <flux:tab name="exchange-tokens">{{ __('Exchange Tokens') }}</flux:tab>
            <flux:tab name="transfer-zen">{{ __('Transfer Zen') }}</flux:tab>
        </flux:tabs>

        <flux:tab.panel name="send-gift">
            <livewire:pages.wallet.send-gift/>
        </flux:tab.panel>
        <flux:tab.panel name="exchange-tokens">
            <livewire:pages.wallet.exchange-tokens/>
        </flux:tab.panel>
        <flux:tab.panel name="transfer-zen">
            <livewire:pages.wallet.transfer-zen/>
        </flux:tab.panel>
    </flux:tab-group>
</div>
