<?php

use App\Actions\Castle\WithdrawFromCastle;
use App\Models\Game\CastleData;
use Livewire\Volt\Component;

new class extends Component {
    public int $treasury = 0;
    public ?string $withdrawType = 'custom';
    public ?int $amount = null;
    public CastleData $castle;

    public function mount(int $treasury, CastleData $castle)
    {
        $this->treasury = $treasury;
        $this->castle   = $castle;
    }


    public function withdraw(): void
    {
        if ($this->withdrawType !== 'custom') {
            $this->amount = floor($this->treasury * (intval($this->withdrawType) / 100));
        }

        $this->validate([
            'withdrawType' => ['required', 'in:25,50,75,100,custom'],
            'amount'       => ['required', 'numeric', 'min:1', "max:{$this->treasury}"],
        ]);

        $result = (new WithdrawFromCastle(
            auth()->user(),
            $this->castle,
            $this->amount
        ))->handle();

        if ($result) {
            $this->dispatch('treasury-updated', treasury: $this->castle->fresh()->MONEY);
            $this->treasury = $this->castle->fresh()->MONEY;
            $this->reset(['amount', 'withdrawType']);
        }
    }
}; ?>

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
            label="Amount"
            type="number"
            x-bind:disabled="withdrawType !== 'custom'"
            :min="1"
            placeholder="Enter amount to withdraw"
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
