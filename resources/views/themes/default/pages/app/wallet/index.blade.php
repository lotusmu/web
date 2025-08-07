<div class="space-y-8">
    <livewire:pages.app.dashboard.card/>

    <flux:tab.group variant="flush">
        <flux:tabs wire:model="tab" class="max-sm:hidden">
            <flux:tab name="send-gift" icon="gift">{{ __('Send Gift') }}</flux:tab>
            <flux:tab name="exchange-tokens" icon="arrows-right-left">{{ __('Exchange Tokens') }}</flux:tab>
            <flux:tab name="transfer" icon="banknotes">{{ __('Transfer Zen') }}</flux:tab>
        </flux:tabs>

        <flux:tabs wire:model="tab" variant="segmented" size="sm" class="sm:hidden mx-auto w-full">
            <flux:tab name="send-gift">{{ __('Send Gift') }}</flux:tab>
            <flux:tab name="exchange-tokens">{{ __('Exchange Tokens') }}</flux:tab>
            <flux:tab name="transfer">{{ __('Transfer Zen') }}</flux:tab>
        </flux:tabs>

        <flux:tab.panel name="send-gift">
            <livewire:pages.app.wallet.send-gift/>
        </flux:tab.panel>
        <flux:tab.panel name="exchange-tokens">
            <livewire:pages.app.wallet.exchange-tokens/>
        </flux:tab.panel>
        <flux:tab.panel name="transfer">
            <livewire:pages.app.wallet.transfer/>
        </flux:tab.panel>
    </flux:tab-group>
</div>
