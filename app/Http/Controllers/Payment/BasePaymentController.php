<?php

namespace App\Http\Controllers\Payment;

use App\Actions\Payment\CreateOrder;
use App\Actions\Payment\HandlePaymentError;
use App\Actions\Payment\UpdateOrderStatus;
use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Interfaces\PaymentGateway;
use App\Models\Payment\Order;
use App\Services\Payment\PaymentLogger;
use Exception;
use Illuminate\Http\RedirectResponse;

abstract class BasePaymentController extends Controller
{
    public function __construct(
        protected readonly CreateOrder $createOrder,
        protected readonly PaymentLogger $logger,
        protected readonly UpdateOrderStatus $updateOrderStatus
    ) {}

    private PaymentGateway $gateway;

    protected function getGateway(): PaymentGateway
    {
        return $this->gateway;
    }

    protected function setGateway(PaymentGateway $gateway): void
    {
        $this->gateway = $gateway;
    }

    protected readonly HandlePaymentError $handleError;

    public function getLogger(): PaymentLogger
    {
        return $this->logger;
    }

    protected function validateOrder(Order $order): ?RedirectResponse
    {
        if ($order->status !== OrderStatus::PENDING) {
            return $this->handleError->handle(
                __('This payment session is no longer valid. Please start a new purchase.')
            );
        }

        if ($order->expires_at?->isPast()) {
            $order->update(['status' => OrderStatus::EXPIRED]);

            return $this->handleError->handle(
                __('Payment session expired. Please start a new purchase.')
            );
        }

        return null;
    }

    protected function logError(string $context, Exception $e, array $extra = []): void
    {
        $this->getGateway()->getLogger()->logError(
            provider: $this->getGateway()->getProviderName(),
            method: $context,
            e: $e,
            context: $extra
        );
    }
}
