<?php

namespace App\Services\Payment;

use App\Actions\Payment\CreateOrder;
use App\Actions\Payment\UpdateOrderStatus;
use App\Enums\OrderStatus;
use App\Interfaces\PaymentGateway;
use App\Models\Payment\Order;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

abstract class BasePaymentGateway implements PaymentGateway
{
    public function __construct(
        protected readonly CreateOrder $createOrder,
        protected readonly UpdateOrderStatus $updateOrderStatus
    ) {}

    abstract public function getProviderName(): string;

    protected function processWebhookWithLock(string $webhookId, callable $processor)
    {
        $lockKey = "webhook_processing_{$webhookId}";

        return Cache::lock($lockKey, 30)->get(function () use ($processor) {
            return $processor();
        });
    }

    public function cancelOrder(Order $order): bool
    {
        try {
            if (! $order->isValidForProcessing()) {
                return false;
            }

            return $this->updateOrderStatus->handle(
                order: $order,
                newStatus: OrderStatus::CANCELLED,
                paymentData: ['cancelled_at' => now()]
            );
        } catch (Exception $e) {
            $this->logError($e, 'cancelOrder');

            return false;
        }
    }

    protected function logError(Exception $e, string $method, array $context = []): void
    {
        Log::error("{$this->getProviderName()} {$method} error", [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            ...$context,
        ]);
    }
}
