<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Enums\PaymentProvider;
use App\Interfaces\PaymentGateway;
use App\Models\Order;
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
                return redirect()->route('donate');
            }

            if ($order->expires_at?->isPast()) {
                $order->update(['status' => OrderStatus::EXPIRED]);

                return $this->errorResponse('Order expired');
            }

            return $this->gateway->processOrder($order)
                ? $this->successResponse()
                : $this->errorResponse('Payment failed');

        } catch (Exception $e) {
            $this->logError('process', $e, ['order_id' => $order->id]);

            return $this->errorResponse('Payment system error');
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
                : $this->errorResponse('Payment failed');

        } catch (Exception $e) {
            $this->logError('success', $e);

            return $this->errorResponse('Payment error');
        }
    }

    public function cancel(Order $order): RedirectResponse
    {
        return $this->gateway->cancelOrder($order)
            ? redirect()->route('donate')->with('info', 'Payment cancelled')
            : $this->errorResponse('Error cancelling payment');
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
        return redirect()->route('dashboard')->with('success', 'Payment completed');
    }

    private function errorResponse(string $message): RedirectResponse
    {
        return redirect()->route('donate')->with('error', $message);
    }

    private function logError(string $method, Exception $e, array $context = []): void
    {
        Log::error("PayPal {$method} error", [
            'error' => $e->getMessage(),
            ...$context,
        ]);
    }
}
