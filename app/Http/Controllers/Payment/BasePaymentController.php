<?php

namespace App\Http\Controllers\Payment;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Interfaces\PaymentGateway;
use App\Models\Payment\Order;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

abstract class BasePaymentController extends Controller
{
    protected PaymentGateway $gateway;

    protected function successResponse(): RedirectResponse
    {
        return redirect()
            ->route('dashboard')
            ->with('toast', [
                'text' => __('Your tokens have been successfully added to your account.'),
                'heading' => __('Purchase Successful'),
                'variant' => 'success',
            ]);
    }

    protected function errorResponse(string $message): RedirectResponse
    {
        return redirect()
            ->route('donate')
            ->with('toast', [
                'text' => $message,
                'heading' => __('Payment Issue'),
                'variant' => 'danger',
            ]);
    }

    protected function validateOrder(Order $order): ?RedirectResponse
    {
        if ($order->status !== OrderStatus::PENDING) {
            return redirect()
                ->route('donate')
                ->with('toast', [
                    'text' => __('This payment session is no longer valid. Please start a new purchase.'),
                    'heading' => __('Invalid Session'),
                    'variant' => 'warning',
                ]);
        }

        if ($order->expires_at?->isPast()) {
            $order->update(['status' => OrderStatus::EXPIRED]);

            return $this->errorResponse(__('Payment session expired'));
        }

        return null;
    }

    protected function logError(string $context, Exception $e, array $extra = []): void
    {
        Log::error("{$context} error", [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            ...$extra,
        ]);
    }
}
