<?php

namespace App\Actions\Partner;

use App\Actions\User\SendNotification;
use App\Enums\Partner\PartnerStatus;
use App\Enums\Utility\ActivityType;
use App\Enums\Utility\ResourceType;
use App\Models\Partner\Partner;
use App\Models\Partner\PromoCodeUsage;
use App\Models\User\User;
use App\Support\ActivityLog\IdentityProperties;
use Illuminate\Support\Str;

class ProcessPromoCode
{
    public function validatePromoCode(string $promoCode): array
    {
        $partner = Partner::where('promo_code', $promoCode)
            ->where('status', PartnerStatus::ACTIVE)
            ->first();

        if (! $partner) {
            return [
                'valid' => false,
                'message' => __('Invalid or inactive promo code.'),
            ];
        }

        // Check if user is trying to use their own promo code
        if ($partner->user_id === auth()->id()) {
            return [
                'valid' => false,
                'message' => __('You cannot use your own promo code.'),
            ];
        }

        return [
            'valid' => true,
            'partner' => $partner,
            'discount_percentage' => 10, // Always 10% extra tokens for users
            'partner_percentage' => $partner->token_percentage,
            'message' => __('Valid promo code! You\'ll get 10% extra tokens.'),
        ];
    }

    public function handle(
        string $promoCode,
        User $user,
        float $donationAmount,
        int $baseTokens, // Base package tokens
        int $tokensBeforePromoBonus, // Base + package bonus (excludes promo bonus)
        ?string $transactionId = null
    ): array {
        // Find partner by promo code
        $partner = Partner::where('promo_code', $promoCode)
            ->where('status', PartnerStatus::ACTIVE)
            ->first();

        if (! $partner) {
            return [
                'success' => false,
                'message' => __('Invalid or inactive promo code.'),
            ];
        }

        // Check if user is trying to use their own promo code
        if ($partner->user_id === $user->id) {
            return [
                'success' => false,
                'message' => __('You cannot use your own promo code.'),
            ];
        }

        // Calculate tokens
        $userExtraTokens = $this->calculateUserExtraTokens($baseTokens); // 10% of base
        $partnerTokens = $this->calculatePartnerTokens($tokensBeforePromoBonus, $partner->token_percentage);

        // Record the usage
        $usage = PromoCodeUsage::create([
            'partner_id' => $partner->id,
            'user_id' => $user->id,
            'promo_code' => $promoCode,
            'donation_amount' => $donationAmount,
            'partner_tokens' => $partnerTokens,
            'user_extra_tokens' => $userExtraTokens,
            'transaction_id' => $transactionId,
        ]);

        // Give partner their tokens
        $partner->user->resource(ResourceType::TOKENS)->increment($partnerTokens);

        // Log partner activity
        $this->logPartnerActivity($partner, $user, $donationAmount, $partnerTokens, $tokensBeforePromoBonus);

        // Send partner notification
        $this->sendPartnerNotification($partner, $user, $partnerTokens, $donationAmount);

        return [
            'success' => true,
            'partner' => $partner,
            'usage' => $usage,
            'user_extra_tokens' => $userExtraTokens,
            'partner_tokens' => $partnerTokens,
            'message' => __('Promo code applied successfully! You got :extra extra tokens.', [
                'extra' => number_format($userExtraTokens),
            ]),
        ];
    }

    private function logPartnerActivity(Partner $partner, User $user, float $donationAmount, int $partnerTokens, int $packageTokens): void
    {
        activity('partner_referral')
            ->performedOn($partner->user)
            ->withProperties([
                'activity_type' => ActivityType::INCREMENT->value,
                'amount' => $partnerTokens,
                'referral_user' => $user->name,
                'donation_amount' => $donationAmount,
                'package_tokens' => $packageTokens,
                'partner_percentage' => $partner->token_percentage,
                'promo_code' => $partner->promo_code,
                'resource_type' => Str::title(ResourceType::TOKENS->value),
                ...IdentityProperties::capture(),
            ])
            ->log('Earned :properties.amount tokens from donation via promo code :properties.promo_code.');
    }

    private function sendPartnerNotification(Partner $partner, User $user, int $partnerTokens, float $donationAmount): void
    {
        SendNotification::make('Referral Reward Earned')
            ->body('Great news! Someone used your promo code and you earned :tokens tokens from their donation.', [
                'user' => $user->name,
                'tokens' => number_format($partnerTokens),
                'amount' => number_format($donationAmount, 2),
            ])
            ->action('View Dashboard', route('partners.dashboard'))
            ->send($partner->user);
    }

    private function calculateUserExtraTokens(int $packageTokens): int
    {
        // User gets 10% of base package tokens (additive bonus)
        return (int) round($packageTokens * 0.10);
    }

    private function calculatePartnerTokens(int $tokensBeforePromoBonus, float $partnerPercentage): int
    {
        // Partner gets their percentage of tokens BEFORE their promo bonus is applied
        return (int) round($tokensBeforePromoBonus * ($partnerPercentage / 100));
    }
}
