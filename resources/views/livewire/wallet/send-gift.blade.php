<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<div>
    <header>
        <flux:heading size="lg">
            {{ __('Send Gift') }}
        </flux:heading>

        <x-flux::subheading>
            {{ __('Transfer tokens, credits, or zen to another user.') }}
        </x-flux::subheading>
    </header>

    <form class="mt-6 space-y-6">
        <flux:select variant="listbox" placeholder="{{__('Choose currency type...')}}">
            <flux:option>{{__('Tokens')}}</flux:option>
            <flux:option>{{__('Credits')}}</flux:option>
            <flux:option>{{__('Zen')}}</flux:option>
        </flux:select>

        <div x-data="{
                amount: 0,
                get totalWithTax() {
                    return this.amount > 0 ? Math.ceil(this.amount * 1.05) : 0;
                }
            }"
             class="grid sm:grid-cols-2 items-end gap-4">
            <flux:input
                type="number"
                label="{{ __('Amount') }}"
                x-model.number="amount"
                @input="amount = parseInt($event.target.value) || 0"
                min="0"
                step="1"
            />
            <flux:input
                type="number"
                label="{{ __('Total (including 5% tax)') }}"
                x-bind:value="totalWithTax"
                disabled
            />
        </div>

        <flux:input
            type="text"
            label="{{ __('Recipient') }}"
            x-model="recipient"
            placeholder="{{ __('Enter character name') }}"
        />

        <flux:textarea
            label="{{ __('Message (optional)') }}"
            x-model="message"
            placeholder="{{ __('Add a short message to your gift...') }}"
            rows="3"
        />

        <flux:button type="submit" variant="primary">
            {{ __('Send') }}
        </flux:button>
    </form>
</div>
