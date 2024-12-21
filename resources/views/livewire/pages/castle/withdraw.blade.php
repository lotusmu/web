<?php

use App\Actions\Castle\WithdrawFromCastle;
use App\Models\Game\CastleData;
use Livewire\Volt\Component;

new class extends Component {
    public int $treasury = 0;
    public ?string $selected = 'custom';
    public ?int $amount = null;
    public CastleData $castle;

    public function mount(int $treasury, CastleData $castle)
    {
        $this->treasury = $treasury;
        $this->castle   = $castle;
    }

    public function withdraw(): void
    {
        $amount = $this->selected === 'custom'
            ? $this->amount
            : floor($this->treasury * (intval($this->selected) / 100));

        $result = (new WithdrawFromCastle(
            auth()->user(),
            $this->castle,
            $amount
        ))->handle();

        if ($result) {
            $this->reset(['amount', 'selected']);

            $this->dispatch('treasury-updated', treasury: $this->castle->fresh()->MONEY);
        }
    }
}; ?>

<form wire:submit="withdraw">
    <flux:card
        class="space-y-6"
        x-data="{
            selected: 'custom',
            amount: null,
            maxAmount: {{ $this->treasury }},
            calculateAmount() {
                if (this.selected !== 'custom') {
                    return Math.floor(this.maxAmount * (parseInt(this.selected) / 100));
                }
                return this.amount;
            }
        }"
    >
        <flux:heading size="lg">
            {{__('Quick Withdraw')}}
        </flux:heading>

        <flux:radio.group
            variant="cards"
            :indicator="false"
            class="max-sm:flex-col text-center"
            x-model="selected"
            wire:model="selected"
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
            type="number"
            x-bind:disabled="selected !== 'custom'"
            :min="1"
            placeholder="Enter amount to withdraw"
            x-bind:value="selected !== 'custom' ? calculateAmount() : amount"
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
