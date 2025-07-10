<?php

use App\Actions\Partner\ProcessPromoCode;
use App\Enums\PaymentProvider;
use App\Models\Payment\TokenPackage;
use App\Services\Payment\PaymentGatewayFactory;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {
    public $selectedPackage = null;
    public $paymentMethod;
    public $packages;
    public $terms = false;

    public $showPromoCode = false;
    public $promoCode = '';
    public $promoCodeStatus = null;
    public $promoData = null;

    public function updatedPromoCode()
    {
        if (empty($this->promoCode)) {
            $this->resetPromoCode();

            return;
        }

        // Real promo code validation
        $validator = app(ProcessPromoCode::class);
        $result    = $validator->validatePromoCode($this->promoCode);

        if ($result['valid']) {
            $this->promoCodeStatus = 'valid';
            $this->promoData       = $result;
        } else {
            $this->promoCodeStatus = 'invalid';
            $this->promoData       = null;
        }
    }

    private function resetPromoCode(): void
    {
        $this->promoCodeStatus = null;
        $this->promoData       = null;
    }

    public function mount(): void
    {
        $this->packages = TokenPackage::all();
    }

    public function checkout()
    {
        $this->validate([
            'selectedPackage' => 'required',
            'paymentMethod'   => 'required',
            'terms'           => 'accepted',
        ], [
            'selectedPackage.required' => __('Please select a package.'),
            'paymentMethod.required'   => __('Please select a payment method.'),
            'terms.accepted'           => __('You must agree to the Terms of Service to continue.'),
        ]);

        // Verify package exists and is valid
        $package = TokenPackage::find($this->selectedPackage);
        if ( ! $package) {
            Flux::toast(
                text: __('Selected package is no longer available.'),
                heading: __('Invalid Package'),
                variant: 'danger'
            );

            return;
        }

        // Verify payment provider is valid using enum
        $validProviders = array_column(PaymentProvider::cases(), 'value');
        if ( ! in_array($this->paymentMethod, $validProviders)) {
            Flux::toast(
                text: __('Invalid payment method selected.'),
                heading: __('Payment Error'),
                variant: 'danger'
            );

            return;
        }

        // Validate promo code if entered
        if ($this->promoCode && $this->promoCodeStatus !== 'valid') {
            Flux::toast(
                text: __('Please fix the promo code error before continuing.'),
                heading: __('Invalid Promo Code'),
                variant: 'danger'
            );

            return;
        }

        $package = TokenPackage::find($this->selectedPackage);

        try {
            $gateway = PaymentGatewayFactory::create($this->paymentMethod);

            // Pass promo code data through session for order processing
            if ($this->promoCodeStatus === 'valid') {
                session([
                    'checkout_promo_code' => [
                        'code'              => $this->promoCode,
                        'partner_id'        => $this->promoData['partner']->id,
                        'user_extra_tokens' => $this->getUserExtraTokens($package),
                        'partner_tokens'    => $this->getPartnerTokens($package),
                    ]
                ]);
            } else {
                // Clear any existing promo code session data
                session()->forget('checkout_promo_code');
            }

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
        $baseAmount   = $package->tokens_amount;
        $packageBonus = $package->bonus_rate > 0 ? $package->bonus_rate : 0;
        $promoBonus   = $this->promoCodeStatus === 'valid' ? 10 : 0;

        // Additive calculation: both bonuses apply to base amount
        $totalBonusPercentage = $packageBonus + $promoBonus;
        $finalAmount          = $baseAmount + ($baseAmount * $totalBonusPercentage / 100);

        return [
            'amount'        => number_format($finalAmount),
            'bonus'         => $totalBonusPercentage > 0 ? $totalBonusPercentage : null,
            'promo_applied' => $promoBonus > 0,
            'package_bonus' => $packageBonus,
            'promo_bonus'   => $promoBonus,
        ];
    }

    private function getUserExtraTokens(TokenPackage $package): int
    {
        // User gets 10% of base tokens (additive with package bonus)
        return (int) round($package->tokens_amount * 0.10);
    }

    private function getPartnerTokens(TokenPackage $package): int
    {
        if ($this->promoCodeStatus !== 'valid' || ! $this->promoData) {
            return 0;
        }

        // Partner gets percentage of tokens BEFORE their promo bonus
        // This includes base tokens + package bonus, but excludes promo bonus
        $baseTokens             = $package->tokens_amount;
        $packageBonus           = $package->bonus_rate > 0 ? $package->bonus_rate : 0;
        $tokensBeforePromoBonus = $baseTokens + ($baseTokens * $packageBonus / 100);

        $partnerPercentage = $this->promoData['partner_percentage'];

        return (int) round($tokensBeforePromoBonus * ($partnerPercentage / 100));
    }

    public function getPromoMessage(): string
    {
        if ($this->promoCodeStatus === 'valid' && $this->promoData) {
            return __('Nice! You\'ll get +10% extra tokens.');
        }

        return __('Hmm, that code isn\'t working');
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

    <!-- Promo Code Accordion -->
    <flux:card>
        <flux:accordion transition>
            <flux:accordion.item>
                <flux:accordion.heading>
                    <div class="flex items-center gap-3">
                        <flux:icon.tag class="w-5 h-5"/>
                        <span>{{ __('Got a promo code?') }}</span>
                        @if($promoCodeStatus === 'valid')
                            <flux:badge size="sm" color="green" inset="top bottom">
                                {{ __('Applied') }}
                            </flux:badge>
                        @endif
                    </div>
                </flux:accordion.heading>

                <flux:accordion.content>
                    <div class="space-y-3">
                        <flux:input
                            wire:model.live.debounce.500ms="promoCode"
                            placeholder="{{ __('Enter promo code') }}"
                            class="mt-1"
                        />

                        @if($promoCodeStatus === 'valid')
                            <flux:text class="flex items-center gap-2 !text-green-600 dark:!text-green-400">
                                <flux:icon.check class="w-4 h-4"/>
                                <span>{{ $this->getPromoMessage() }}</span>
                            </flux:text>
                        @elseif($promoCodeStatus === 'invalid')
                            <flux:text class="flex items-center gap-2 !text-red-600 dark:!text-red-400">
                                <flux:icon.x-mark class="w-4 h-4"/>
                                <span>{{ $this->getPromoMessage() }}</span>
                            </flux:text>
                        @endif

                        @if(!$promoCode)
                            <flux:text>
                                {{ __('Enter your favorite content creator\'s code to earn bonus tokens') }}
                            </flux:text>
                        @endif
                    </div>
                </flux:accordion.content>
            </flux:accordion.item>
        </flux:accordion>
    </flux:card>

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

    <flux:field variant="inline">
        <flux:checkbox wire:model="terms"/>
        <flux:label>
            {{__('I agree to the ')}}
            <flux:link href="{{ route('terms') }}" target="_blank">{{ __('Terms of Service') }}</flux:link>
        </flux:label>

        <flux:error name="terms"/>
    </flux:field>

    <flux:button
        wire:click="checkout"
        variant="primary"
        icon-trailing="chevron-right"
        class="w-full">
        {{__('Continue to Payment')}}
    </flux:button>

</div>
