<?php

namespace App\Actions\Payment;

use App\Enums\OrderStatus;
use App\Enums\Utility\ResourceType;
use App\Models\Payment\Order;
use Illuminate\Support\Facades\DB;

class UpdateOrderStatus
{
    public function __construct(
        private readonly LogPurchaseActivity $logPurchase,
        private readonly LogFailedPurchase $logFailure,
        private readonly LogRefundActivity $logRefund
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
        $order->user->resource(ResourceType::TOKENS)
            ->increment($order->package->tokens_amount);
        $this->logPurchase->handle($order);
    }

    private function handleRefundedOrder(Order $order): void
    {
        $order->user->resource(ResourceType::TOKENS)
            ->decrement($order->package->tokens_amount);
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
