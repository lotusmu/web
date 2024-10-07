<?php

use App\Actions\ExchangeResources;
use App\Enums\Utility\OperationType;
use App\Models\Concerns\Taxable;
use App\Models\User\User;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new class extends Component {
    use Taxable;

    public User $user;

    #[Validate('required|integer|min:1')]
    public int $amount = 0;

    public function mount(): void
    {
        $this->user          = auth()->user();
        $this->operationType = OperationType::EXCHANGE;
        $this->initializeTaxable();
    }

    public function exchange(ExchangeResources $action): void
    {
        $this->validate();

        $taxAmount = $this->calculateTax($this->amount);

        $action->handle($this->user, $this->amount, $taxAmount);

        $this->reset('amount');
    }
}; ?>

<div>
    <header>
        <flux:heading size="lg">
            {{ __('Exchange Tokens') }}
        </flux:heading>

        <x-flux::subheading>
            {{ __('Exchange your tokens for credits.') }}
        </x-flux::subheading>
    </header>

    <form wire:submit="exchange" class="mt-6 space-y-6">
        <div x-data="{
                amount: $wire.entangle('amount'),
                taxRate: {{ $this->taxRate }},
                get totalWithTax() {
                    if (this.amount <= 0) return 0;
                    const taxAmount = Math.round(this.amount * (this.taxRate / 100));
                    return this.amount - taxAmount;
                }
            }" class="grid sm:grid-cols-2 items-start gap-4">
            <flux:input
                wire:model="amount"
                x-model.number="amount"
                type="number"
                label="{{ __('Amount') }}"
                min="0"
                step="1"
            />
            <flux:input
                x-bind:value="new Intl.NumberFormat().format(totalWithTax)"
                type="text"
                label="{{ __('Amount after tax (' . $this->taxRate . '% tax)') }}"
                disabled
            />
        </div>
        <flux:button type="submit" variant="primary">
            {{ __('Submit') }}
        </flux:button>
    </form>
</div>
