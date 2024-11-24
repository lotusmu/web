<?php

namespace App\Services\Payment;

use App\Enums\OrderStatus;
use App\Enums\PaymentProvider;
use App\Enums\Utility\ActivityType;
use App\Enums\Utility\ResourceType;
use App\Interfaces\PaymentGateway;
use App\Models\Order;
use App\Models\TokenPackage;
use App\Models\User\User;
use App\Support\ActivityLog\IdentityProperties;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class StripeGateway implements PaymentGateway
{
    public function initiateCheckout(User $user, TokenPackage $package): mixed
    {
        return $user->checkout($package->stripe_price_id, [
            'success_url' => route('checkout.success').'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.cancel'),
            'mode' => 'payment',
            'metadata' => ['package_id' => $package->id],
            'payment_intent_data' => [
                'setup_future_usage' => 'off_session',
                'metadata' => ['package_id' => $package->id],
            ],
        ]);
    }

    public function handleWebhook(array $payload): mixed
    {
        try {
            return match ($payload['type']) {
                'checkout.session.completed' => $this->handleCheckoutCompleted($payload['data']['object']),
                'payment_intent.created' => $this->handlePaymentIntentCreated($payload['data']['object']),
                'payment_intent.payment_failed' => $this->handlePaymentFailed($payload['data']['object']),
                'charge.failed' => $this->handleChargeFailed($payload['data']['object']),
                'charge.refunded' => $this->handleRefund($payload['data']['object']),
                default => null,
            };
        } catch (Exception $e) {
            $this->logError('handleWebhook', $e);
            throw $e;
        }
    }

    public function processOrder(Order $order): bool
    {
        return true; // Stripe handles via webhooks
    }

    public function verifyWebhookSignature(string $payload, array $headers): bool
    {
        return true; // Handled by Cashier
    }

    public function cancelOrder(Order $order): bool
    {
        try {
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

    private function handleCheckoutCompleted(array $session): mixed
    {
        $user = User::where('stripe_id', $session['customer'])->first();
        $package = TokenPackage::find($session['metadata']['package_id'] ?? null);

        if (! $user || ! $package) {
            return null;
        }

        DB::transaction(function () use ($session, $user, $package) {
            $order = Order::where('payment_id', $session['payment_intent'])->first();

            if ($order) {
                $order->update([
                    'status' => OrderStatus::COMPLETED,
                    'payment_data' => $session,
                ]);

                $user->resource(ResourceType::TOKENS)->increment($package->tokens_amount);
                $this->logPurchaseActivity($user, $package, $session);
            }
        });

        return null;
    }

    private function handlePaymentIntentCreated(array $intent): mixed
    {
        $failureData = $this->getFailureData($intent['id']);

        if ($order = $this->createOrder($intent, $failureData)) {
            $this->cleanupFailureData($failureData);
        }

        return null;
    }

    private function handlePaymentFailed(array $intent): mixed
    {
        $failureData = [
            'failure_message' => $intent['last_payment_error']['message'] ?? 'Payment failed',
            'failure_code' => $intent['last_payment_error']['code'] ?? null,
        ];

        if ($order = $this->updateOrderToFailed($intent['id'], $failureData)) {
            $this->logFailedPurchase($order, $failureData['failure_message']);
        }

        return null;
    }

    private function handleChargeFailed(array $charge): mixed
    {
        $failureData = [
            'failure_message' => $charge['failure_message'],
            'failure_code' => $charge['failure_code'],
        ];

        if (! $this->updateOrderToFailed($charge['payment_intent'], $failureData)) {
            $this->storeFailureData($charge['payment_intent'], $failureData);
        }

        return null;
    }

    private function handleRefund(array $charge): mixed
    {
        $order = Order::where('payment_id', $charge['payment_intent'])->first();

        if ($order) {
            DB::transaction(function () use ($order, $charge) {
                $order->update([
                    'status' => OrderStatus::REFUNDED,
                    'payment_data' => [...$order->payment_data ?? [],
                        'refund_id' => $charge['refunds']['data'][0]['id'] ?? null,
                        'refund_reason' => $charge['refunds']['data'][0]['reason'] ?? null,
                        'refund_amount' => ($charge['amount_refunded'] ?? 0) / 100,
                    ],
                ]);

                $order->user->resource(ResourceType::TOKENS)->decrement($order->package->tokens_amount);
                $this->logRefundActivity($order, $charge);
            });
        }

        return null;
    }

    private function createOrder(array $intent, ?object $failureData = null): ?Order
    {
        $user = User::where('stripe_id', $intent['customer'])->first();
        $package = TokenPackage::find($intent['metadata']['package_id'] ?? null);

        if (! $user || ! $package) {
            return null;
        }

        $order = Order::create([
            'user_id' => $user->id,
            'token_package_id' => $package->id,
            'payment_provider' => PaymentProvider::STRIPE,
            'payment_id' => $intent['id'],
            'amount' => $intent['amount'] / 100,
            'currency' => $intent['currency'],
            'status' => $failureData ? OrderStatus::FAILED : OrderStatus::PENDING,
            'payment_data' => $failureData ? json_decode($failureData->failure_data, true) : $intent,
        ]);

        if ($failureData && $order) {
            $this->logFailedPurchase($order, $failureData->failure_data['failure_message'] ?? 'Payment failed');
        }

        return $order;
    }

    private function updateOrderToFailed(string $paymentIntentId, array $failureData): ?Order
    {
        $order = Order::where('payment_id', $paymentIntentId)->first();

        if ($order) {
            $order->update([
                'status' => OrderStatus::FAILED,
                'payment_data' => [...$order->payment_data ?? [], ...$failureData],
            ]);
        }

        return $order;
    }

    private function getFailureData(string $paymentIntentId): ?object
    {
        return DB::table('payment_failures')
            ->where('payment_intent_id', $paymentIntentId)
            ->first();
    }

    private function storeFailureData(string $paymentIntentId, array $failureData): void
    {
        DB::table('payment_failures')->insert([
            'payment_intent_id' => $paymentIntentId,
            'failure_data' => json_encode($failureData),
            'created_at' => now(),
        ]);
    }

    private function cleanupFailureData(?object $failureData): void
    {
        if ($failureData) {
            DB::table('payment_failures')->where('id', $failureData->id)->delete();
        }
    }

    private function logPurchaseActivity(User $user, TokenPackage $package, array $session): void
    {
        activity('token_purchase')
            ->performedOn($user)
            ->withProperties([
                'activity_type' => ActivityType::INCREMENT->value,
                'package_name' => $package->name,
                'amount' => $package->tokens_amount,
                'price' => ($session['amount_total'] / 100).' '.strtoupper($session['currency']),
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

    private function logRefundActivity(Order $order, array $charge): void
    {
        activity('token_purchase')
            ->performedOn($order->user)
            ->withProperties([
                'activity_type' => ActivityType::DECREMENT->value,
                'package_name' => $order->package->name,
                'amount' => $order->package->tokens_amount,
                'price' => "{$order->amount} {$order->currency}",
                'resource_type' => Str::title(ResourceType::TOKENS->value),
                ...IdentityProperties::capture(),
            ])
            ->log(':properties.resource_type purchase refunded. Package: :properties.package_name.');
    }

    private function logError(string $method, Exception $e): void
    {
        Log::error("Stripe {$method} error", [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
    }
}
