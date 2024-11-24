<?php

use App\Enums\OrderStatus;
use App\Enums\PaymentProvider;
use App\Models\Order;
use App\Models\TokenPackage;
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
        if ( ! $this->selectedPackage) {
            return;
        }

        $package = TokenPackage::find($this->selectedPackage);

        return match ($this->paymentMethod) {
            'stripe' => auth()->user()->checkout($package->stripe_price_id, [
                'success_url'         => route('checkout.success').'?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url'          => route('checkout.cancel'),
                'mode'                => 'payment',
                'metadata'            => [
                    'package_id' => $package->id
                ],
                'payment_intent_data' => [
                    'setup_future_usage' => 'off_session',
                    'metadata'           => [
                        'package_id' => $package->id
                    ]
                ],
            ]),
            'paypal' => $this->initPaypalCheckout($package),
        };
    }

    private function initPaypalCheckout(TokenPackage $package)
    {
        $order = Order::firstOrCreate(
            [
                'user_id'          => auth()->id(),
                'token_package_id' => $package->id,
                'status'           => OrderStatus::PENDING,
            ],
            [
                'payment_provider' => PaymentProvider::PAYPAL,
                'payment_id'       => 'pp_'.Str::random(20),
                'amount'           => $package->price,
                'currency'         => 'EUR',
                'expires_at'       => now()->addMinutes(30),
            ]
        );

        return redirect()->route('paypal.checkout', $order->id);
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

        <flux:radio value="coinbase" checked>
            <div class="flex flex-col items-center gap-2 text-center w-full">
                <img class="h-6" src="{{ asset('images/payments/coingate-icon.svg') }}" alt="CoinGate Brand Logo">
                <flux:heading class="leading-4">CoinGate</flux:heading>
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
