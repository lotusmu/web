<?php

namespace App\Services\Payment;

use App\Enums\OrderStatus;
use App\Enums\PaymentProvider;
use App\Enums\Utility\ActivityType;
use App\Enums\Utility\ResourceType;
use App\Interfaces\PaymentGateway;
use App\Models\Payment\Order;
use App\Models\Payment\TokenPackage;
use App\Models\User\User;
use App\Support\ActivityLog\IdentityProperties;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PayPalGateway implements PaymentGateway
{
    private const EVENT_PAYMENT_COMPLETED = 'PAYMENT.CAPTURE.COMPLETED';

    private const EVENT_PAYMENT_DENIED = 'PAYMENT.CAPTURE.DENIED';

    private const EVENT_PAYMENT_REFUNDED = 'PAYMENT.CAPTURE.REFUNDED';

    private $client;

    public function __construct()
    {
        $this->client = $this->getPayPalHttpClient();
    }

    public function initiateCheckout(User $user, TokenPackage $package): mixed
    {
        $order = Order::firstOrCreate(
            [
                'user_id' => $user->id,
                'token_package_id' => $package->id,
                'status' => OrderStatus::PENDING,
            ],
            [
                'payment_provider' => PaymentProvider::PAYPAL,
                'payment_id' => 'pp_'.Str::random(20),
                'amount' => $package->price,
                'currency' => 'EUR',
                'expires_at' => now()->addMinutes(30),
            ]
        );

        try {
            $response = $this->client->post('/v2/checkout/orders', [
                'intent' => 'CAPTURE',
                'purchase_units' => [[
                    'reference_id' => $order->id,
                    'amount' => [
                        'currency_code' => $order->currency,
                        'value' => number_format($order->amount, 2, '.', ''),
                    ],
                ]],
                'application_context' => [
                    'return_url' => route('checkout.paypal.success', ['token' => '%id%']),
                    'cancel_url' => route('checkout.paypal.cancel', $order->id),
                ],
            ]);

            if ($response->successful()) {
                $order->update(['payment_id' => $response->json()['id']]);

                return $response->json()['links'][1]['href'];
            }

            throw new Exception('PayPal payment initialization failed');
        } catch (Exception $e) {
            $this->logError('initiateCheckout', $e);
            throw $e;
        }
    }

    public function processOrder(Order $order): bool
    {
        try {
            if ($order->status !== OrderStatus::PENDING) {
                return false;
            }

            $response = $this->client->post(
                "/v2/checkout/orders/{$order->payment_id}/capture",
                [
                    'headers' => ['Content-Type' => 'application/json'],
                    'body' => '{}',
                ]
            );

            if ($response->successful()) {
                DB::transaction(function () use ($order, $response) {
                    $order->update([
                        'status' => OrderStatus::COMPLETED,
                        'payment_data' => $response->json(),
                    ]);

                    $order->user->resource(ResourceType::TOKENS)
                        ->increment($order->package->tokens_amount);
                    $this->logPurchaseActivity($order);
                });

                return true;
            }

            return false;
        } catch (Exception $e) {
            $this->logError('processOrder', $e);

            return false;
        }
    }

    public function handleWebhook(array $payload): mixed
    {
        try {
            return match ($payload['event_type']) {
                self::EVENT_PAYMENT_COMPLETED => $this->handlePaymentCompleted($payload['resource']),
                self::EVENT_PAYMENT_DENIED => $this->handlePaymentDenied($payload['resource']),
                self::EVENT_PAYMENT_REFUNDED => $this->handleRefund($payload['resource']),
                default => null,
            };
        } catch (Exception $e) {
            $this->logError('handleWebhook', $e);
            throw $e;
        }
    }

    public function verifyWebhookSignature(string $payload, array $headers): bool
    {
        try {
            $response = $this->client->post('/v1/notifications/verify-webhook-signature', [
                'webhook_id' => config('services.paypal.webhook_id'),
                'transmission_id' => $headers['PAYPAL-TRANSMISSION-ID'],
                'transmission_time' => $headers['PAYPAL-TRANSMISSION-TIME'],
                'transmission_sig' => $headers['PAYPAL-TRANSMISSION-SIG'],
                'cert_url' => $headers['PAYPAL-CERT-URL'],
                'auth_algo' => $headers['PAYPAL-AUTH-ALGO'],
                'webhook_event' => json_decode($payload, true),
            ]);

            return $response->json()['verification_status'] === 'SUCCESS';
        } catch (Exception $e) {
            $this->logError('verifyWebhookSignature', $e);

            return false;
        }
    }

    public function cancelOrder(Order $order): bool
    {
        try {
            if ($order->status !== OrderStatus::PENDING) {
                return false;
            }

            $order->update([
                'status' => OrderStatus::CANCELLED,
                'payment_data' => [...$order->payment_data ?? [], 'cancelled_at' => now()],
            ]);

            return true;
        } catch (Exception $e) {
            $this->logError('cancelOrder', $e);

            return false;
        }
    }

    private function handlePaymentCompleted(array $resource): mixed
    {
        $order = $this->findPendingOrder($resource);

        if (! $order) {
            return null;
        }

        DB::transaction(function () use ($order, $resource) {
            $order->update([
                'status' => OrderStatus::COMPLETED,
                'payment_data' => [...$order->payment_data ?? [], ...$this->getPaymentData($resource)],
            ]);

            $order->user->resource(ResourceType::TOKENS)->increment($order->package->tokens_amount);
            $this->logPurchaseActivity($order);
        });

        return null;
    }

    private function handlePaymentDenied(array $resource): mixed
    {
        $order = $this->findPendingOrder($resource);

        if (! $order) {
            return null;
        }

        $order->update([
            'status' => OrderStatus::FAILED,
            'payment_data' => [...$order->payment_data ?? [], ...$this->getFailureData($resource)],
        ]);

        $this->logFailedPurchase($order, $resource['status_details']['reason'] ?? 'Payment denied');

        return null;
    }

    private function handleRefund(array $resource): mixed
    {
        $order = $this->findCompletedOrder($resource);

        if (! $order) {
            return null;
        }

        DB::transaction(function () use ($order, $resource) {
            $order->update([
                'status' => OrderStatus::REFUNDED,
                'payment_data' => [...$order->payment_data ?? [], ...$this->getRefundData($resource)],
            ]);

            $order->user->resource(ResourceType::TOKENS)->decrement($order->package->tokens_amount);
            $this->logRefundActivity($order, $resource);
        });

        return null;
    }

    private function findPendingOrder(array $resource): ?Order
    {
        return Order::where('payment_id', $resource['id'])
            ->where('status', OrderStatus::PENDING)
            ->first();
    }

    private function findCompletedOrder(array $resource): ?Order
    {
        return Order::where('status', OrderStatus::COMPLETED)
            ->where(function ($query) use ($resource) {
                $query->where('payment_id', $resource['parent_payment'])
                    ->orWhere('payment_id', $resource['id'])
                    ->orWhereJsonContains('payment_data->paypal_capture_id', $resource['id']);
            })
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

    private function logPurchaseActivity(Order $order): void
    {
        activity('token_purchase')
            ->performedOn($order->user)
            ->withProperties([
                'activity_type' => ActivityType::INCREMENT->value,
                'package_name' => $order->package->name,
                'amount' => $order->package->tokens_amount,
                'price' => "{$order->amount} {$order->currency}",
                'resource_type' => Str::title(ResourceType::TOKENS->value),
                ...IdentityProperties::capture(),
            ])
            ->log('Purchased :properties.package_name.');
    }

    private function logFailedPurchase(Order $order, string $failureReason): void
    {
        activity('token_purchase')
            ->performedOn($order->user)
            ->withProperties([
                'activity_type' => ActivityType::DEFAULT->value,
                'package_name' => $order->package->name,
                'price' => "{$order->amount} {$order->currency}",
                'resource_type' => Str::title(ResourceType::TOKENS->value),
                'failure_reason' => $failureReason,
                ...IdentityProperties::capture(),
            ])
            ->log(':properties.resource_type purchase failed. Reason: :properties.failure_reason');
    }

    private function logRefundActivity(Order $order, array $resource): void
    {
        activity('token_purchase')
            ->performedOn($order->user)
            ->withProperties([
                'activity_type' => ActivityType::DECREMENT->value,
                'package_name' => $order->package->name,
                'amount' => $order->package->tokens_amount,
                'price' => "{$order->amount} {$order->currency}",
                'resource_type' => Str::title(ResourceType::TOKENS->value),
                'refund_reason' => $resource['status_details']['reason'] ?? 'No reason provided',
                ...IdentityProperties::capture(),
            ])
            ->log(':properties.resource_type purchase refunded. Package: :properties.package_name.');
    }

    private function logError(string $method, Exception $e): void
    {
        Log::error("PayPal {$method} error", [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
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
