<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Enums\PaymentProvider;
use App\Interfaces\PaymentGateway;
use App\Models\Payment\Order;
use App\Services\Payment\PaymentGatewayFactory;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PayPalController extends Controller
{
    private PaymentGateway $gateway;

    public function __construct(PaymentGatewayFactory $gatewayFactory)
    {
        $this->gateway = $gatewayFactory->create(PaymentProvider::PAYPAL);
    }

    public function process(Order $order): RedirectResponse
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

                return $this->errorResponse(
                    __('Your payment session has timed out for security. Please start a new purchase.')
                );
            }

            return $this->gateway->processOrder($order)
                ? $this->successResponse()
                : $this->errorResponse(
                    __('We couldn\'t complete your payment. Please try again or use a different payment method.')
                );

        } catch (Exception $e) {
            $this->logError('process', $e, ['order_id' => $order->id]);

            return $this->errorResponse(
                __('We encountered a technical issue. Please try again or contact support if the problem persists.')
            );
        }
    }

    public function success(Request $request): RedirectResponse
    {
        try {
            $order = Order::where('payment_id', $request->token)
                ->where('status', OrderStatus::PENDING)
                ->firstOrFail();

            return $this->gateway->processOrder($order)
                ? $this->successResponse()
                : $this->errorResponse(
                    __('We couldn\'t complete your payment. The transaction was declined or cancelled.')
                );

        } catch (Exception $e) {
            $this->logError('success', $e);

            return $this->errorResponse(
                __('We couldn\'t verify your payment status. If your account was charged, please contact support.')
            );
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
            : $this->errorResponse(__('Unable to cancel payment. Please contact support if you see any charges.'));
    }

    public function webhook(Request $request): JsonResponse
    {
        try {
            if (! $this->gateway->verifyWebhookSignature($request->getContent(), $request->headers->all())) {
                return response()->json(['error' => 'Invalid signature'], 400);
            }

            $this->gateway->handleWebhook(json_decode($request->getContent(), true));

            return response()->json(['status' => 'ok']);

        } catch (Exception $e) {
            $this->logError('webhook', $e);

            return response()->json(['error' => 'Webhook processing failed'], 500);
        }
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
        $errorMessage = match (true) {
            str_contains($message, 'expired') => __('Your payment session has expired. Please try again.'),
            str_contains($message, 'declined') => __('Your payment was declined. Please check your payment details and try again.'),
            str_contains($message, 'cancelled') => __('You cancelled the payment process. Your account has not been charged.'),
            default => __('We encountered an issue processing your payment. Please try again or contact support.')
        };

        return redirect()
            ->route('donate')
            ->with('toast', [
                'text' => $errorMessage,
                'heading' => __('Payment Issue'),
                'variant' => 'danger',
            ]);
    }

    private function logError(string $method, Exception $e, array $context = []): void
    {
        Log::error("PayPal {$method} error", [
            'error' => $e->getMessage(),
            ...$context,
        ]);
    }
}
