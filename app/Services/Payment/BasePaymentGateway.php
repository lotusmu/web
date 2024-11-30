<?php

namespace App\Services\Payment;

use App\Actions\Payment\CreateOrder;
use App\Actions\Payment\UpdateOrderStatus;
use App\Enums\OrderStatus;
use App\Interfaces\PaymentGateway;
use App\Models\Payment\Order;
use Exception;

abstract class BasePaymentGateway implements PaymentGateway
{
    public function __construct(
        protected readonly CreateOrder $createOrder,
        protected readonly PaymentLogger $logger,
        protected readonly UpdateOrderStatus $updateOrderStatus
    ) {}

    public function getLogger(): PaymentLogger
    {
        return $this->logger;
    }

    abstract public function getProviderName(): string;

    public function cancelOrder(Order $order): bool
    {
        try {
            if ($order->status !== OrderStatus::PENDING) {
                return false;
            }

            return $this->updateOrderStatus->handle(
                order: $order,
                newStatus: OrderStatus::CANCELLED,
                paymentData: ['cancelled_at' => now()]
            );
        } catch (Exception $e) {
            $this->handleError($e, 'cancelOrder');

            return false;
        }
    }

    protected function handleError(Exception $e, string $method, array $context = []): void
    {
        $this->logger->logError($this->getProviderName(), $method, $e, $context);
    }

    protected function findOrderById(string $orderId): ?Order
    {
        return Order::findOrFail($orderId);
    }

    protected function findPendingOrderByPaymentId(string $paymentId): ?Order
    {
        return Order::where('payment_id', $paymentId)
            ->where('status', OrderStatus::PENDING)
            ->first();
    }

    protected function findCompletedOrderByPaymentId(string $paymentId): ?Order
    {
        return Order::where('payment_id', $paymentId)
            ->where('status', OrderStatus::COMPLETED)
            ->first();
    }
}
