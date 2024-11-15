<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {
    //
}; ?>

<div class="space-y-6">
    <header>
        <flux:heading size="xl">
            {{ __('Get Tokens') }}
        </flux:heading>

        <x-flux::subheading>
            {{ __('Choose a package that works best for you.') }}
        </x-flux::subheading>
    </header>

    <flux:radio.group label="Choose Your Package" variant="cards" class="grid grid-cols-2 max-sm:grid-cols-1">
        <flux:radio value="standard" checked>
            <flux:radio.indicator/>

            <div class="flex-1">
                <flux:subheading size="sm">
                    Bronze Package
                </flux:subheading>
                <flux:heading class="leading-4">€ 20</flux:heading>
                <flux:subheading size="sm">500 tokens</flux:subheading>
            </div>
        </flux:radio>

        <flux:radio value="standard">
            <flux:radio.indicator/>

            <div class="flex-1">
                <flux:subheading size="sm" class="flex items-center">
                    <span>Silver Package</span>
                    <flux:spacer/>
                    <flux:badge size="sm" color="green" inset="top bottom">
                        Most popular
                    </flux:badge>
                </flux:subheading>
                <flux:heading class="leading-4">€ 40</flux:heading>
                <flux:subheading size="sm">500 tokens</flux:subheading>
            </div>
        </flux:radio>

        <flux:radio value="standard">
            <flux:radio.indicator/>

            <div class="flex-1">
                <flux:subheading size="sm">
                    Gold Package
                </flux:subheading>
                <flux:heading class="leading-4">€ 80</flux:heading>
                <flux:subheading size="sm">500 tokens</flux:subheading>
            </div>
        </flux:radio>

        <flux:radio value="standard">
            <flux:radio.indicator/>

            <div class="flex-1">
                <flux:subheading size="sm">
                    Platinum Package
                </flux:subheading>
                <flux:heading class="leading-4">€ 160</flux:heading>
                <flux:subheading size="sm">500 tokens</flux:subheading>
            </div>
        </flux:radio>

        <flux:radio value="standard">
            <flux:radio.indicator/>

            <div class="flex-1">
                <flux:subheading size="sm">
                    Diamond Package
                </flux:subheading>
                <flux:heading class="leading-4">€ 320</flux:heading>
                <flux:subheading size="sm">500 tokens</flux:subheading>
            </div>
        </flux:radio>
    </flux:radio.group>

    <flux:radio.group label="Payment method" variant="cards" :indicator="false"
                      class="flex max-sm:flex-col">
        <flux:radio value="stripe" checked>
            <div class="flex flex-col items-center gap-2 text-center w-full">
                <img class="w-6" src="{{ asset('images/payments/stripe-icon.svg') }}" alt="Stripe Brand Logo">
                <flux:heading class="leading-4">Stripe</flux:heading>
            </div>
        </flux:radio>

        <flux:radio value="paypal" checked>
            <div class="flex flex-col items-center gap-2 text-center w-full">
                <img class="w-6" src="{{ asset('images/payments/paypal-icon.svg') }}" alt="Pay Pal Brand Logo">
                <flux:heading class="leading-4">Pay Pal</flux:heading>
            </div>
        </flux:radio>

        <flux:radio value="coinbase" checked>
            <div class="flex flex-col items-center gap-2 text-center w-full">
                <img class="w-6" src="{{ asset('images/payments/coinbase-icon.svg') }}" alt="Coinbase Brand Logo">
                <flux:heading class="leading-4">Coinbase</flux:heading>
            </div>
        </flux:radio>
    </flux:radio.group>

    <flux:button variant="primary" icon-trailing="chevron-right" class="w-full">
        Continue to Payment
    </flux:button>
</div>
