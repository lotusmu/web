<?php

namespace App\Actions\Payment;

use App\Actions\User\SendNotification;
use App\Enums\Utility\ActivityType;
use App\Enums\Utility\ResourceType;
use App\Models\Payment\Order;
use App\Support\ActivityLog\IdentityProperties;
use Illuminate\Support\Str;

class LogPurchaseActivity
{
    public function handle(Order $order, ?int $totalTokensReceived = null): void
    {
        // Calculate total tokens (base + any promo bonus)
        $baseTokens = $order->package->tokens_amount;
        $actualTokensReceived = $totalTokensReceived ?? $baseTokens;

        // Check if promo code was used
        $promoData = $order->payment_data['promo_code'] ?? null;
        $promoBonus = $promoData['user_extra_tokens'] ?? 0;

        $logProperties = [
            'activity_type' => ActivityType::INCREMENT->value,
            'package_name' => $order->package->name,
            'base_amount' => $baseTokens,
            'amount' => $actualTokensReceived,
            'price' => "{$order->amount} {$order->currency}",
            'resource_type' => Str::title(ResourceType::TOKENS->value),
            ...IdentityProperties::capture(),
        ];

        // Add promo code info if used
        if ($promoData) {
            $logProperties['promo_code'] = $promoData['code'];
            $logProperties['promo_bonus'] = $promoBonus;
        }

        activity('token_purchase')
            ->performedOn($order->user)
            ->withProperties($logProperties)
            ->log($this->getLogMessage($promoData, $baseTokens, $promoBonus));

        $this->sendNotification($order, $actualTokensReceived, $promoBonus);
    }

    private function getLogMessage(?array $promoData, int $baseTokens, int $promoBonus): string
    {
        if ($promoData && $promoBonus > 0) {
            return 'Purchased :properties.package_name with promo code :properties.promo_code (+:properties.promo_bonus bonus tokens).';
        }

        return 'Purchased :properties.package_name.';
    }

    private function sendNotification(Order $order, int $totalTokens, int $promoBonus): void
    {
        if ($promoBonus > 0) {
            SendNotification::make('Tokens Received')
                ->body('Your purchase of ":package_name" is complete. :total_tokens tokens have been added to your account (:base_tokens + :bonus_tokens bonus).', [
                    'package_name' => $order->package->name,
                    'total_tokens' => number_format($totalTokens),
                    'base_tokens' => number_format($order->package->tokens_amount),
                    'bonus_tokens' => number_format($promoBonus),
                ])
                ->send($order->user);
        } else {
            SendNotification::make('Tokens Received')
                ->body('Your purchase of ":package_name" is complete. :amount tokens have been added to your account.', [
                    'package_name' => $order->package->name,
                    'amount' => number_format($totalTokens),
                ])
                ->send($order->user);
        }
    }
}
