<?php

namespace App\Livewire\Pages\App\Donate;

use App\Actions\Partner\ProcessPromoCode;
use App\Enums\PaymentProvider;
use App\Livewire\BaseComponent;
use App\Models\Payment\TokenPackage;
use App\Services\Payment\PaymentGatewayFactory;
use Flux\Flux;

class Donate extends BaseComponent
{
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
        $result = $validator->validatePromoCode($this->promoCode);

        if ($result['valid']) {
            $this->promoCodeStatus = 'valid';
            $this->promoData = $result;
        } else {
            $this->promoCodeStatus = 'invalid';
            $this->promoData = null;
        }
    }

    private function resetPromoCode(): void
    {
        $this->promoCodeStatus = null;
        $this->promoData = null;
    }

    public function mount(): void
    {
        $this->packages = TokenPackage::all();
    }

    public function checkout()
    {
        $this->validate([
            'selectedPackage' => 'required',
            'paymentMethod' => 'required',
            'terms' => 'accepted',
        ], [
            'selectedPackage.required' => __('Please select a package.'),
            'paymentMethod.required' => __('Please select a payment method.'),
            'terms.accepted' => __('You must agree to the Terms of Service to continue.'),
        ]);

        // Verify package exists and is valid
        $package = TokenPackage::find($this->selectedPackage);
        if (! $package) {
            Flux::toast(
                text: __('Selected package is no longer available.'),
                heading: __('Invalid Package'),
                variant: 'danger'
            );

            return;
        }

        // Verify payment provider is valid using enum
        $validProviders = array_column(PaymentProvider::cases(), 'value');
        if (! in_array($this->paymentMethod, $validProviders)) {
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
                        'code' => $this->promoCode,
                        'partner_id' => $this->promoData['partner']->id,
                        'user_extra_tokens' => $this->getUserExtraTokens($package),
                        'partner_tokens' => $this->getPartnerTokens($package),
                    ],
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
                'trace' => $e->getTraceAsString(),
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
        $baseAmount = $package->tokens_amount;
        $packageBonus = $package->bonus_rate > 0 ? $package->bonus_rate : 0;
        $promoBonus = $this->promoCodeStatus === 'valid' ? 10 : 0;

        // Additive calculation: both bonuses apply to base amount
        $totalBonusPercentage = $packageBonus + $promoBonus;
        $finalAmount = $baseAmount + ($baseAmount * $totalBonusPercentage / 100);

        return [
            'amount' => number_format($finalAmount),
            'bonus' => $totalBonusPercentage > 0 ? $totalBonusPercentage : null,
            'promo_applied' => $promoBonus > 0,
            'package_bonus' => $packageBonus,
            'promo_bonus' => $promoBonus,
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
        $baseTokens = $package->tokens_amount;
        $packageBonus = $package->bonus_rate > 0 ? $package->bonus_rate : 0;
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

    protected function getViewName(): string
    {
        return 'pages.app.donate.index';
    }

    protected function getLayoutType(): string
    {
        return 'app';
    }
}
