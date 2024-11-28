<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Enums\PaymentProvider;
use App\Interfaces\PaymentGateway;
use App\Models\Payment\Order;
use App\Services\Payment\PaymentGatewayFactory;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PrimeController extends Controller
{
    private PaymentGateway $gateway;

    public function __construct(PaymentGatewayFactory $gatewayFactory)
    {
        $this->gateway = $gatewayFactory->create(PaymentProvider::PRIME);
    }

    public function webhook(Request $request)
    {
        try {
            if (! $this->gateway->verifyWebhookSignature($request->getContent(), $request->headers->all())) {
                return response()->json(['error' => 'Invalid signature'], 400);
            }

            $this->gateway->handleWebhook(json_decode($request->getContent(), true));

            return response('OK');  // Plain text OK as required
        } catch (Exception $e) {
            Log::error('Prime webhook error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response('ERROR', 500);
        }
    }

    public function success(Order $order): RedirectResponse
    {
        try {
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

            return $this->gateway->processOrder($order)
                ? $this->successResponse()
                : $this->errorResponse(__('Payment verification failed'));

        } catch (Exception $e) {
            Log::error('Prime success callback error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'order' => $order->id,
            ]);

            return $this->errorResponse(__('Payment verification failed'));
        }
    }

    public function cancel(Order $order): RedirectResponse
    {
        return $this->gateway->cancelOrder($order)
            ? redirect()
                ->route('donate')
                ->with('toast', [
                    'text' => __('Payment process was cancelled. Your account has not been charged.'),
                    'heading' => __('Payment Cancelled'),
                    'variant' => 'warning',
                ])
            : $this->errorResponse(__('Unable to cancel payment'));
    }

    private function successResponse(): RedirectResponse
    {
        return redirect()
            ->route('dashboard')
            ->with('toast', [
                'text' => __('Your tokens have been successfully added to your account.'),
                'heading' => __('Purchase Successful'),
                'variant' => 'success',
            ]);
    }

    private function errorResponse(string $message): RedirectResponse
    {
        return redirect()
            ->route('donate')
            ->with('toast', [
                'text' => $message,
                'heading' => __('Payment Issue'),
                'variant' => 'danger',
            ]);
    }
}
