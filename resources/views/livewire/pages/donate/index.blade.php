<?php

use App\Enums\OrderStatus;
use App\Enums\PaymentProvider;
use App\Models\Order;
use App\Models\TokenPackage;
use App\Services\Payment\PaymentGatewayFactory;
use Illuminate\Http\RedirectResponse;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
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

            // PayPal returns direct URL
            $this->redirect($checkoutResponse);
        } catch (Exception $e) {
            Log::error('Checkout Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            Flux::toast('Payment initialization failed');

            return redirect()->back();
        }
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
        label="Choose Your Package"
        variant="cards"
        class="grid grid-cols-2 max-sm:grid-cols-1">

        @foreach($packages as $package)
            <flux:radio value="{{ $package->id }}">
                <flux:radio.indicator/>

                <div class="flex-1">
                    <flux:subheading size="sm" class="flex items-center">
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
                    <flux:subheading size="sm">{{ $package->tokens_amount }} {{__('tokens')}}</flux:subheading>
                </div>
            </flux:radio>
        @endforeach
    </flux:radio.group>

    <flux:radio.group label="Payment method" variant="cards" :indicator="false"
                      class="flex max-sm:flex-col" wire:model="paymentMethod">
        <flux:radio value="stripe" checked>
            <div class="flex flex-col items-center gap-2 text-center w-full">
                <img class="w-6 h-6" src="{{ asset('images/payments/stripe-icon.svg') }}" alt="Stripe Brand Logo">
                <flux:heading class="leading-4">Stripe</flux:heading>
            </div>
        </flux:radio>

        <flux:radio value="paypal" checked>
            <div class="flex flex-col items-center gap-2 text-center w-full">
                <img class="w-6 h-6" src="{{ asset('images/payments/paypal-icon.svg') }}" alt="PayPal Brand Logo">
                <flux:heading class="leading-4">PayPal</flux:heading>
            </div>
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

@push('scripts')
    <script
        src="https://www.paypal.com/sdk/js?client-id={{ config('services.paypal.client_id') }}&currency=EUR"></script>
@endpush
