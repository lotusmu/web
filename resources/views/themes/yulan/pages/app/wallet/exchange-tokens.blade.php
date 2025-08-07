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
                clearable
                wire:model="amount"
                type="number"
                label="{{ __('Amount') }}"
                min="0"
                step="1"
            />
            <flux:input
                x-bind:value="new Intl.NumberFormat().format(totalWithTax)"
                type="text"
                label="{{ __('Amount after tax (:rate% tax)', ['rate' => $this->taxRate]) }}"
                disabled
            />
        </div>
        <flux:button type="submit" variant="primary">
            {{ __('Submit') }}
        </flux:button>
    </form>
</div>