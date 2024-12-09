<?php

namespace App\Http\Controllers\Payment;

use App\Actions\Payment\HandlePaymentError;
use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Interfaces\PaymentGateway;
use App\Models\Payment\Order;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

abstract class BasePaymentController extends Controller
{
    private PaymentGateway $gateway;

    protected readonly HandlePaymentError $handleError;

    protected function getGateway(): PaymentGateway
    {
        return $this->gateway;
    }

    protected function setGateway(PaymentGateway $gateway): void
    {
        $this->gateway = $gateway;
    }

    protected function validateOrder(Order $order): ?RedirectResponse
    {
        if (! $order->isValidForProcessing()) {
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
        Log::error("{$this->getGateway()->getProviderName()} {$context} error", [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            ...$extra,
        ]);
    }
}
