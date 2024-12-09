<?php

namespace App\Services\Payment\PayPal;

use App\Actions\Payment\CreateOrder;
use App\Actions\Payment\UpdateOrderStatus;
use App\Enums\OrderStatus;
use App\Enums\PaymentProvider;
use App\Models\Payment\Order;
use App\Models\Payment\TokenPackage;
use App\Models\User\User;
use App\Services\Payment\BasePaymentGateway;
use Exception;

class PayPalGateway extends BasePaymentGateway
{
    private const EVENT_PAYMENT_COMPLETED = 'PAYMENT.CAPTURE.COMPLETED';

    private const EVENT_PAYMENT_DENIED = 'PAYMENT.CAPTURE.DENIED';

    private const EVENT_PAYMENT_REFUNDED = 'PAYMENT.CAPTURE.REFUNDED';

    public function __construct(
        CreateOrder $createOrder,
        UpdateOrderStatus $updateOrderStatus,
        private readonly PayPalClient $client
    ) {
        parent::__construct($createOrder, $updateOrderStatus);
    }

    public function getProviderName(): string
    {
        return 'PayPal';
    }

    public function initiateCheckout(User $user, TokenPackage $package): mixed
    {
        $order = $this->createOrder->handle(
            user: $user,
            package: $package,
            provider: PaymentProvider::PAYPAL
        );

        try {
            $response = $this->client->get()->post('/v2/checkout/orders', [
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
            $this->logError($e, 'initiateCheckout');
            throw $e;
        }
    }

    public function processOrder(Order $order): bool
    {
        try {
            if (! $order->isValidForProcessing()) {
                return false;
            }

            $response = $this->client->get()->post(
                "/v2/checkout/orders/{$order->payment_id}/capture",
                [
                    'headers' => ['Content-Type' => 'application/json'],
                    'body' => '{}',
                ]
            );

            if ($response->successful()) {
                return $this->updateOrderStatus->handle(
                    order: $order,
                    newStatus: OrderStatus::COMPLETED,
                    paymentData: $response->json()
                );
            }

            return false;
        } catch (Exception $e) {
            $this->logError($e, 'processOrder');

            return false;
        }
    }

    public function handleWebhook(array $payload): mixed
    {
        try {
            $webhookId = $payload['id'] ?? '';

            return $this->processWebhookWithLock($webhookId, function () use ($payload) {
                return match ($payload['event_type']) {
                    self::EVENT_PAYMENT_COMPLETED => $this->handlePaymentCompleted($payload['resource']),
                    self::EVENT_PAYMENT_DENIED => $this->handlePaymentDenied($payload['resource']),
                    self::EVENT_PAYMENT_REFUNDED => $this->handleRefund($payload['resource']),
                    default => null,
                };
            });
        } catch (Exception $e) {
            $this->logError($e, 'handleWebhook');
            throw $e;
        }
    }

    public function verifyWebhookSignature(string $payload, array $headers): bool
    {
        try {
            $response = $this->client->get()->post('/v1/notifications/verify-webhook-signature', [
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
            $this->logError($e, 'verifyWebhookSignature');

            return false;
        }
    }

    private function handlePaymentCompleted(array $resource): mixed
    {
        $order = $this->findPayPalPendingOrder($resource);
        if (! $order) {
            return null;
        }

        $this->updateOrderStatus->handle(
            order: $order,
            newStatus: OrderStatus::COMPLETED,
            paymentData: $this->getPaymentData($resource)
        );

        return null;
    }

    private function handlePaymentDenied(array $resource): mixed
    {
        $order = $this->findPayPalPendingOrder($resource);
        if (! $order) {
            return null;
        }

        $this->updateOrderStatus->handle(
            order: $order,
            newStatus: OrderStatus::FAILED,
            paymentData: [
                ...$this->getFailureData($resource),
                'failure_reason' => $resource['status_details']['reason'] ?? 'Payment denied',
            ]
        );

        return null;
    }

    private function handleRefund(array $resource): mixed
    {
        $order = $this->findPayPalCompletedOrder($resource);
        if (! $order) {
            return null;
        }

        $this->updateOrderStatus->handle(
            order: $order,
            newStatus: OrderStatus::REFUNDED,
            paymentData: $this->getRefundData($resource)
        );

        return null;
    }

    private function findPayPalPendingOrder(array $resource): ?Order
    {
        return Order::where('payment_id', $resource['id'])
            ->where('status', OrderStatus::PENDING)
            ->first();
    }

    private function findPayPalCompletedOrder(array $resource): ?Order
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
}
