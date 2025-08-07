@php
use App\Enums\Utility\ResourceType;
@endphp

<div>
    <header>
        <flux:heading size="lg">
            {{ __('Send Gift') }}
        </flux:heading>

        <x-flux::subheading>
            {{ __('Transfer tokens, credits, or zen to another user.') }}
        </x-flux::subheading>
    </header>

    <form wire:submit="transfer" class="mt-6 space-y-6">
        <flux:select wire:model="resourceType" variant="listbox" placeholder="{{__('Choose resource type...')}}">
            @foreach(ResourceType::cases() as $type)
                @if($type !== ResourceType::LUCKY_TICKETS)
                    <flux:option value="{{ $type->value }}">{{ __($type->getLabel()) }}</flux:option>
                @endif
            @endforeach
        </flux:select>

        <flux:error name="resourceType"/>

        <div x-data="{
                amount: $wire.entangle('amount'),
                taxRate: {{ $this->taxRate }},
                get totalWithTax() {
                    if (this.amount <= 0) return 0;
                    const taxAmount = Math.round(this.amount * (this.taxRate / 100));
                    return this.amount + taxAmount;
                }
            }" class="grid sm:grid-cols-2 items-start gap-4">
            <flux:input
                clearable
                label="{{ __('Amount') }}"
                wire:model="amount"
                x-model.number="amount"
                type="number"
                min="0"
                step="1"
            />
            <flux:input
                label="{{ __('Total (including :rate% tax)', ['rate' => $this->taxRate]) }}"
                x-bind:value="new Intl.NumberFormat().format(totalWithTax)"
                type="text"
                disabled
            />
        </div>

        <flux:autocomplete
            wire:model.live.debounce.300ms="recipient"
            label="{{ __('Recipient') }}"
            placeholder="{{ __('Enter character name') }}"
            :filter="false"
        >
            @foreach($suggestions as $name)
                <flux:autocomplete.item wire:key="{{ $name }}">{{ $name }}</flux:autocomplete.item>
            @endforeach
        </flux:autocomplete>

        <flux:button type="submit" variant="primary">
            {{ __('Send') }}
        </flux:button>
    </form>
</div>