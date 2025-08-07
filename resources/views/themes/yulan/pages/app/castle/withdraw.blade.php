<form wire:submit="withdraw">
    <flux:card
        class="space-y-6"
        x-data="{
            withdrawType: 'custom',
            amount: null,
            maxAmount: {{ $this->treasury }},
            calculateAmount() {
                if (this.withdrawType !== 'custom') {
                    return Math.floor(this.maxAmount * (parseInt(this.withdrawType) / 100));
                }
                return this.amount;
            }
        }"
        x-init="
            $wire.on('treasury-updated', ({ treasury }) => {
                maxAmount = treasury;
                amount = null;  // Reset Alpine amount
                withdrawType = 'custom';  // Reset Alpine withdrawType
            });
        "
        @change="
            if (withdrawType === 'custom') {
                amount = null;
            }
        "
    >
        <flux:heading size="lg">
            {{__('Quick Withdraw')}}
        </flux:heading>

        <flux:radio.group
            variant="cards"
            :indicator="false"
            class="max-sm:flex-col text-center"
            x-model="withdrawType"
            wire:model="withdrawType"
        >
            <flux:radio value="25" label="25%"/>
            <flux:radio value="50" label="50%"/>
            <flux:radio value="75" label="75%"/>
            <flux:radio value="100" label="100%"/>
            <flux:radio value="custom" label="Custom"/>
        </flux:radio.group>

        <flux:input
            x-model="amount"
            wire:model="amount"
            label="{{__('Amount')}}"
            type="number"
            x-bind:disabled="withdrawType !== 'custom'"
            :min="1"
            placeholder="{{__('Enter amount to withdraw')}}"
            x-bind:value="withdrawType !== 'custom' ? calculateAmount() : amount"
        />

        <flux:button
            type="submit"
            variant="primary"
            icon-trailing="chevron-right"
            class="w-full"
        >
            {{__('Withdraw')}}
        </flux:button>
    </flux:card>
</form>