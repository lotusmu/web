<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Enums\Utility\ResourceType;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class PayPalWebhookController extends Controller
{
    protected $webhookHandlers = [
        'PAYMENT.CAPTURE.COMPLETED' => 'handlePaymentCompleted',
        'PAYMENT.CAPTURE.DENIED' => 'handlePaymentDenied',
        'PAYMENT.CAPTURE.REFUNDED' => 'handlePaymentRefunded',
    ];

    public function handleWebhook(Request $request)
    {
        if (! $this->verifyWebhookSignature($request)) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        $payload = json_decode($request->getContent(), true);

        if (isset($this->webhookHandlers[$payload['event_type']])) {
            $method = $this->webhookHandlers[$payload['event_type']];
            $this->$method($payload['resource']);
        }

        return response()->json(['status' => 'ok']);
    }

    private function handlePaymentCompleted(array $resource)
    {
        if (! $order = $this->findPendingOrder($resource)) {
            return;
        }

        DB::transaction(function () use ($order, $resource) {
            $order->update([
                'status' => OrderStatus::COMPLETED,
                'payment_data' => [...$order->payment_data ?? [], ...$this->getPaymentData($resource)],
            ]);

            $order->user->resource(ResourceType::TOKENS)->increment($order->package->tokens_amount);
        });
    }

    private function handlePaymentDenied(array $resource)
    {
        if (! $order = $this->findPendingOrder($resource)) {
            return;
        }

        $order->update([
            'status' => OrderStatus::FAILED,
            'payment_data' => [...$order->payment_data ?? [], ...$this->getFailureData($resource)],
        ]);
    }

    private function handlePaymentRefunded(array $resource)
    {
        if (! $order = $this->findCompletedOrder($resource)) {
            return;
        }

        DB::transaction(function () use ($order, $resource) {
            $order->update([
                'status' => OrderStatus::REFUNDED,
                'payment_data' => [...$order->payment_data ?? [], ...$this->getRefundData($resource)],
            ]);

            $order->user->resource(ResourceType::TOKENS)->decrement($order->package->tokens_amount);
        });
    }

    private function findPendingOrder(array $resource): ?Order
    {
        return Order::where('payment_id', $resource['supplementary_data']['related_ids']['order_id'])
            ->where('status', OrderStatus::PENDING)
            ->first();
    }

    private function findCompletedOrder(array $resource): ?Order
    {
        return Order::where('payment_id', $resource['supplementary_data']['related_ids']['order_id'])
            ->where('status', OrderStatus::COMPLETED)
            ->first();
    }

    private function getPaymentData(array $resource): array
    {
        return [
            'paypal_capture_id' => $resource['id'],
            'amount' => $resource['amount']['value'],
            'currency' => $resource['amount']['currency_code'],
        ];
    }

    private function getFailureData(array $resource): array
    {
        return [
            'paypal_capture_id' => $resource['id'],
            'failure_reason' => $resource['status_details']['reason'] ?? 'Payment denied',
        ];
    }

    private function getRefundData(array $resource): array
    {
        return [
            'refund_id' => $resource['id'],
            'refund_amount' => $resource['amount']['value'],
            'refund_currency' => $resource['amount']['currency_code'],
        ];
    }

    private function verifyWebhookSignature(Request $request): bool
    {
        $webhookId = config('services.paypal.webhook_id');
        $requestBody = $request->getContent();

        $headers = [
            'transmission_id' => $request->header('PAYPAL-TRANSMISSION-ID'),
            'transmission_time' => $request->header('PAYPAL-TRANSMISSION-TIME'),
            'cert_url' => $request->header('PAYPAL-CERT-URL'),
            'auth_algo' => $request->header('PAYPAL-AUTH-ALGO'),
            'transmission_sig' => $request->header('PAYPAL-TRANSMISSION-SIG'),
        ];

        $verifyResponse = $this->getPayPalHttpClient()->post('/v1/notifications/verify-webhook-signature', [
            'webhook_id' => $webhookId,
            'transmission_id' => $headers['transmission_id'],
            'transmission_time' => $headers['transmission_time'],
            'transmission_sig' => $headers['transmission_sig'],
            'cert_url' => $headers['cert_url'],
            'auth_algo' => $headers['auth_algo'],
            'webhook_event' => json_decode($requestBody, true),
        ]);

        return $verifyResponse['verification_status'] === 'SUCCESS';
    }

    private function getPayPalHttpClient()
    {
        $baseUrl = config('services.paypal.mode') === 'sandbox'
            ? 'https://api-m.sandbox.paypal.com'
            : 'https://api-m.paypal.com';

        return Http::withHeaders([
            'Authorization' => 'Basic '.base64_encode(
                config('services.paypal.client_id').':'.config('services.paypal.secret')
            ),
        ])->baseUrl($baseUrl);
    }
}
