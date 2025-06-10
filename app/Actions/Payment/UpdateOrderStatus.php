<?php

namespace App\Actions\Payment;

use App\Actions\Partner\ProcessPromoCode;
use App\Enums\OrderStatus;
use App\Enums\Utility\ResourceType;
use App\Models\Payment\Order;
use Exception;
use Illuminate\Support\Facades\DB;
use Log;

class UpdateOrderStatus
{
    public function __construct(
        private readonly LogPurchaseActivity $logPurchase,
        private readonly LogFailedPurchase $logFailure,
        private readonly LogRefundActivity $logRefund,
        private readonly ProcessPromoCode $processPromoCode
    ) {}

    public function handle(
        Order $order,
        OrderStatus $newStatus,
        array $paymentData = []
    ): bool {

        if (! $order->canTransitionTo($newStatus)) {
            return false;
        }

        DB::transaction(function () use ($order, $newStatus, $paymentData) {
            $order->statusHistory()->create([
                'from_status' => $order->status,
                'to_status' => $newStatus->value,
                'reason' => $this->getStatusChangeReason($order->status, $newStatus),
            ]);

            $order->update([
                'status' => $newStatus,
                'payment_data' => [...$order->payment_data ?? [], ...$paymentData],
            ]);

            match ($newStatus) {
                OrderStatus::COMPLETED => $this->handleCompletedOrder($order),
                OrderStatus::FAILED => $this->logFailure->handle($order, $paymentData['failure_reason'] ?? 'Unknown error'),
                OrderStatus::REFUNDED => $this->handleRefundedOrder($order),
                default => null
            };
        });

        return true;
    }

    private function handleCompletedOrder(Order $order): void
    {
        $baseTokens = $order->package->tokens_amount;
        $packageBonus = $order->package->bonus_rate > 0 ? $order->package->bonus_rate : 0;

        // Calculate tokens before promo bonus (base + package bonus)
        $tokensBeforePromoBonus = $baseTokens + ($baseTokens * $packageBonus / 100);

        // Process promo code if present
        $promoData = $order->payment_data['promo_code'] ?? null;

        // Calculate total tokens using additive bonus system
        $promoBonus = $promoData ? 10 : 0; // 10% promo bonus if promo code used
        $totalBonusPercentage = $packageBonus + $promoBonus;
        $totalTokens = $baseTokens + ($baseTokens * $totalBonusPercentage / 100);

        if ($promoData) {
            $this->processPromoCodeUsage($order, $promoData, $baseTokens, $tokensBeforePromoBonus);
        }

        // Give user their tokens (base + package bonus + promo bonus)
        $order->user->resource(ResourceType::TOKENS)->increment($totalTokens);

        $this->logPurchase->handle($order, $totalTokens);

        // Clear the promo code session data
        session()->forget('checkout_promo_code');
    }

    private function processPromoCodeUsage(Order $order, array $promoData, int $baseTokens, int $tokensBeforePromoBonus): void
    {
        try {
            // Double-check validation before processing
            $validator = app(ProcessPromoCode::class);
            $validationResult = $validator->validatePromoCode($promoData['code']);

            if (! $validationResult['valid']) {
                Log::warning('Invalid promo code attempted during order completion', [
                    'order_id' => $order->id,
                    'promo_code' => $promoData['code'],
                    'user_id' => $order->user->id,
                    'reason' => $validationResult['message'],
                ]);

                return;
            }

            $this->processPromoCode->handle(
                promoCode: $promoData['code'],
                user: $order->user,
                donationAmount: $order->amount,
                baseTokens: $baseTokens, // Base package tokens
                tokensBeforePromoBonus: $tokensBeforePromoBonus, // Base + package bonus only
                transactionId: $order->getProviderTransactionId() ?? $order->payment_id
            );
        } catch (Exception $e) {
            // Log error but don't fail the order
            Log::error('Failed to process promo code usage', [
                'order_id' => $order->id,
                'promo_code' => $promoData['code'],
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function handleRefundedOrder(Order $order): void
    {
        // Calculate total tokens to remove (base + promo bonus if any)
        $tokensToRemove = $order->package->tokens_amount;

        $promoData = $order->payment_data['promo_code'] ?? null;
        if ($promoData) {
            $userExtraTokens = $promoData['user_extra_tokens'] ?? 0;
            $tokensToRemove += $userExtraTokens;
        }

        $order->user->resource(ResourceType::TOKENS)->decrement($tokensToRemove);
        $this->logRefund->handle($order);
    }

    private function getStatusChangeReason(OrderStatus $fromStatus, OrderStatus $toStatus): ?string
    {
        return match ([$fromStatus, $toStatus]) {
            [OrderStatus::PENDING, OrderStatus::COMPLETED] => 'Payment successful',
            [OrderStatus::PENDING, OrderStatus::FAILED] => 'Payment failed',
            [OrderStatus::PENDING, OrderStatus::CANCELLED] => 'Order cancelled by user',
            [OrderStatus::PENDING, OrderStatus::EXPIRED] => 'Order expired',
            [OrderStatus::COMPLETED, OrderStatus::REFUNDED] => 'Payment refunded',
            [OrderStatus::FAILED, OrderStatus::COMPLETED] => 'Payment successful after failure',
            default => null
        };
    }
}
