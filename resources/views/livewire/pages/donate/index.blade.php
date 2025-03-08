<?php

use App\Enums\PaymentProvider;
use App\Models\Payment\TokenPackage;
use App\Services\Payment\PaymentGatewayFactory;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {
    public $selectedPackage = null;
    public $paymentMethod;
    public $packages;

    public function mount(): void
    {
        $this->packages = TokenPackage::all();
    }

    public function checkout()
    {
        if ( ! $this->selectedPackage || ! $this->paymentMethod) {
            Flux::toast(
                text: __('Please select a package and payment method to continue.'),
                heading: __('Selection Required'),
                variant: 'warning'
            );

            return;
        }

        $package = TokenPackage::find($this->selectedPackage);

        try {
            $gateway          = PaymentGatewayFactory::create($this->paymentMethod);
            $checkoutResponse = $gateway->initiateCheckout(auth()->user(), $package);

            // Stripe returns an object with url property
            if ($this->paymentMethod === PaymentProvider::STRIPE->value) {
                return $this->redirect($checkoutResponse->url);
            }

            // PayPal and Prime return direct URLs
            $this->redirect($checkoutResponse);
        } catch (Exception $e) {
            Log::error('Checkout Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            Flux::toast(
                text: __('Unable to start payment process. Please try again.'),
                heading: __('Payment Error'),
                variant: 'danger'
            );

            return redirect()->back();
        }
    }

    public function getTokenSummary(TokenPackage $package): array
    {
        return [
            'amount' => number_format($package->tokens_amount),
            'bonus'  => $package->bonus_rate > 0 ? $package->bonus_rate : null,
        ];
    }
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

    <flux:radio.group
        wire:model="selectedPackage"
        label="{{__('Choose Your Package')}}"
        variant="cards"
        class="grid grid-cols-2 max-sm:grid-cols-1">

        @foreach($packages as $package)
            <flux:radio value="{{ $package->id }}">
                <flux:radio.indicator/>

                <div class="flex-1">
                    <flux:subheading size="sm" class="flex">
                        <span>
                            {{ $package->name }}
                        </span>
                        <flux:spacer/>
                        @if($package->is_popular)
                            <flux:badge size="sm" color="green" inset="top bottom">
                                Most popular
                            </flux:badge>
                        @endif
                    </flux:subheading>
                    <flux:heading class="leading-4">€ {{ $package->price }}</flux:heading>
                    <flux:subheading size="sm">
                        {{ $this->getTokenSummary($package)['amount'] }} {{ __('tokens') }}
                        @if($this->getTokenSummary($package)['bonus'])
                            <span class="font-bold">·</span>
                            <span class="text-green-600 dark:text-green-500 font-bold">
                                {{ $this->getTokenSummary($package)['bonus'] }}% {{ __('extra') }}
                            </span>
                        @endif
                    </flux:subheading>
                </div>
            </flux:radio>
        @endforeach
    </flux:radio.group>

    <flux:radio.group label="{{__('Payment method')}}" variant="cards" :indicator="false"
                      class="grid grid-cols-1 sm:grid-cols-2" wire:model="paymentMethod">
        <flux:radio value="stripe" class="flex">
            <div class="flex flex-1 items-center gap-4 w-full">
                <img class="w-8 h-8" src="{{ asset('images/payments/stripe-icon.svg') }}" alt="Stripe Brand Logo">
                <div>
                    <flux:heading class="leading-4">
                        Stripe
                    </flux:heading>
                    <flux:subheading>
                        {{__('Fast and secure card processing')}}
                    </flux:subheading>
                </div>
            </div>

            <flux:radio.indicator/>
        </flux:radio>

        <flux:radio value="paypal" class="flex">
            <div class="flex flex-1 items-center gap-4 w-full">
                <img class="w-8 h-8" src="{{ asset('images/payments/paypal-icon.svg') }}" alt="PayPal Brand Logo">
                <div>
                    <flux:heading class="leading-4">
                        PayPal
                    </flux:heading>
                    <flux:subheading>
                        {{__('Safe digital payments worldwide')}}
                    </flux:subheading>
                </div>
            </div>

            <flux:radio.indicator/>
        </flux:radio>

        <flux:radio value="prime" class="flex sm:col-span-2">
            <div class="flex flex-1 items-center gap-4 w-full">
                <img class="w-8 h-8" src="{{ asset('images/payments/prime-icon.svg') }}" alt="PrimePayments Brand Logo">
                <div>
                    <flux:heading class="leading-4">
                        PrimePayments
                    </flux:heading>
                    <flux:subheading>
                        {{__('Full support for Russia, Belarus, sanctioned regions + all major cryptocurrencies')}}
                    </flux:subheading>
                </div>
            </div>

            <flux:radio.indicator/>
        </flux:radio>
    </flux:radio.group>

    <flux:button
        wire:click="checkout"
        variant="primary"
        icon-trailing="chevron-right"
        class="w-full">
        {{__('Continue to Payment')}}
    </flux:button>

</div>
