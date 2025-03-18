<?php

namespace App\Services\Payment\Stripe;

use App\Enums\OrderStatus;
use App\Enums\PaymentProvider;
use App\Models\Payment\Order;
use App\Models\Payment\TokenPackage;
use App\Models\User\User;
use App\Services\Payment\BasePaymentGateway;
use Exception;

class StripeGateway extends BasePaymentGateway
{
    private const EVENT_CHECKOUT_COMPLETED = 'checkout.session.completed';

    private const EVENT_CHECKOUT_EXPIRED = 'checkout.session.expired';

    private const EVENT_PAYMENT_INTENT_CREATED = 'payment_intent.created';

    private const EVENT_PAYMENT_FAILED = 'payment_intent.payment_failed';

    private const EVENT_CHARGE_FAILED = 'charge.failed';

    private const EVENT_CHARGE_REFUNDED = 'charge.refunded';

    public function getProviderName(): string
    {
        return 'Stripe';
    }

    public function initiateCheckout(User $user, TokenPackage $package): mixed
    {
        try {
            $order = $this->createOrder->handle(
                user: $user,
                package: $package,
                provider: PaymentProvider::STRIPE
            );

            return $user->checkout($package->stripe_price_id, [
                'success_url' => route('checkout.success').'?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('checkout.cancel', ['order' => $order->id]),
                'mode' => 'payment',
                'payment_method_types' => ['paypal', 'card'],
                'metadata' => [
                    'order_id' => $order->id,
                    'package_id' => $package->id,
                ],
                'payment_intent_data' => [
                    'setup_future_usage' => 'off_session',
                    'metadata' => [
                        'order_id' => $order->id,
                        'package_id' => $package->id,
                    ],
                ],
            ]);
        } catch (Exception $e) {
            $this->logError($e, 'initiateCheckout');
            throw $e;
        }
    }

    public function processOrder(Order $order): bool
    {
        return true; // Handled via webhooks
    }

    public function verifyWebhookSignature(string $payload, array $headers): bool
    {
        return true; // Handled by Cashier
    }

    public function handleWebhook(array $payload): mixed
    {
        try {
            $webhookId = $payload['data']['object']['id'] ?? '';

            return $this->processWebhookWithLock($webhookId, function () use ($payload) {
                return match ($payload['type']) {
                    self::EVENT_CHECKOUT_COMPLETED => $this->handleCheckoutCompleted($payload['data']['object']),
                    self::EVENT_CHECKOUT_EXPIRED => $this->handleCheckoutExpired($payload['data']['object']),
                    self::EVENT_PAYMENT_INTENT_CREATED => $this->handlePaymentIntentCreated($payload['data']['object']),
                    self::EVENT_PAYMENT_FAILED => $this->handlePaymentFailed($payload['data']['object']),
                    self::EVENT_CHARGE_FAILED => $this->handleChargeFailed($payload['data']['object']),
                    self::EVENT_CHARGE_REFUNDED => $this->handleRefund($payload['data']['object']),
                    default => null,
                };
            });
        } catch (Exception $e) {
            $this->logError($e, 'handleWebhook');
            throw $e;
        }
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

    private function handleCheckoutCompleted(array $session): mixed
    {
        $order = Order::where('payment_id', $session['payment_intent'])->first();

        if ($order) {
            $this->updateOrderStatus->handle(
                order: $order,
                newStatus: OrderStatus::COMPLETED,
                paymentData: $session
            );
        }

        return null;
    }

    private function handleCheckoutExpired(array $session): mixed
    {
        try {
            if (isset($session['metadata']['order_id'])) {
                $order = Order::find($session['metadata']['order_id']);
                if ($order && $order->status === OrderStatus::PENDING) {
                    $this->updateOrderStatus->handle(
                        order: $order,
                        newStatus: OrderStatus::EXPIRED,
                        paymentData: [
                            'expired_at' => now(),
                            'session_id' => $session['id'],
                        ]
                    );
                }
            }

            return null;
        } catch (Exception $e) {
            $this->logError($e, 'handleCheckoutExpired', ['session' => $session]);

            return null;
        }
    }

    private function handlePaymentIntentCreated(array $intent): mixed
    {
        try {
            if (isset($intent['metadata']['order_id'])) {
                $order = Order::find($intent['metadata']['order_id']);
                $order?->update(['payment_id' => $intent['id']]);
            }

            return null;
        } catch (Exception $e) {
            $this->logError($e, 'handlePaymentIntentCreated', ['intent' => $intent]);

            return null;
        }
    }

    private function handlePaymentFailed(array $intent): mixed
    {
        try {
            $order = Order::where('payment_id', $intent['id'])->first();

            if ($order) {
                $this->updateOrderStatus->handle(
                    order: $order,
                    newStatus: OrderStatus::FAILED,
                    paymentData: [
                        'failure_message' => $intent['last_payment_error']['message'] ?? 'Payment failed',
                        'failure_code' => $intent['last_payment_error']['code'] ?? null,
                    ]
                );
            }

            return null;
        } catch (Exception $e) {
            $this->logError($e, 'handlePaymentFailed', ['intent' => $intent]);

            return null;
        }
    }

    private function handleChargeFailed(array $charge): mixed
    {
        try {
            $order = Order::where('payment_id', $charge['payment_intent'])->first();

            if ($order) {
                $this->updateOrderStatus->handle(
                    order: $order,
                    newStatus: OrderStatus::FAILED,
                    paymentData: [
                        'failure_message' => $charge['failure_message'],
                        'failure_code' => $charge['failure_code'],
                    ]
                );
            }

            return null;
        } catch (Exception $e) {
            $this->logError($e, 'handleChargeFailed', ['charge' => $charge]);

            return null;
        }
    }

    private function handleRefund(array $charge): mixed
    {
        $order = Order::where('payment_id', $charge['payment_intent'])->first();

        if ($order) {
            $this->updateOrderStatus->handle(
                order: $order,
                newStatus: OrderStatus::REFUNDED,
                paymentData: [
                    'refund_id' => $charge['refunds']['data'][0]['id'] ?? null,
                    'refund_reason' => $charge['refunds']['data'][0]['reason'] ?? null,
                    'refund_amount' => ($charge['amount_refunded'] ?? 0) / 100,
                ]
            );
        }

        return null;
    }
}
