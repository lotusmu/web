<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {
    #[\Livewire\Attributes\Url]
    public string $tab = 'send-gift';
}; ?>

<div class="space-y-8">
    <livewire:wallet.card/>

    <flux:tab.group variant="flush">
        <flux:tabs wire:model="tab">
            <flux:tab name="send-gift" icon="gift">Send Gift</flux:tab>
            <flux:tab name="exchange-tokens" icon="arrows-right-left">Exchange Tokens</flux:tab>
            <flux:tab name="transfer-zen" icon="banknotes">Transfer Zen</flux:tab>
        </flux:tabs>

        <flux:tab.panel name="send-gift">
            <livewire:wallet.send-gift/>
        </flux:tab.panel>
        <flux:tab.panel name="exchange-tokens">
            <livewire:wallet.exchange-tokens/>
        </flux:tab.panel>
        <flux:tab.panel name="transfer-zen">
            <livewire:wallet.transfer-zen/>
        </flux:tab.panel>
    </flux:tab-group>
</div>
