<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Enums\PaymentProvider;
use App\Enums\Utility\ActivityType;
use App\Enums\Utility\ResourceType;
use App\Models\Order;
use App\Models\TokenPackage;
use App\Models\User\User;
use App\Support\ActivityLog\IdentityProperties;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierWebhookController;

class StripeWebhookController extends CashierWebhookController
{
    private const EVENT_CHECKOUT_COMPLETED = 'checkout.session.completed';

    private const EVENT_PAYMENT_INTENT_CREATED = 'payment_intent.created';

    private const EVENT_PAYMENT_INTENT_FAILED = 'payment_intent.payment_failed';

    private const EVENT_CHARGE_FAILED = 'charge.failed';

    private const EVENT_CHARGE_REFUNDED = 'charge.refunded';

    protected $webhookHandlers = [
        self::EVENT_CHECKOUT_COMPLETED => 'handleCheckoutSessionCompleted',
        self::EVENT_PAYMENT_INTENT_CREATED => 'handlePaymentIntentCreated',
        self::EVENT_PAYMENT_INTENT_FAILED => 'handlePaymentIntentFailed',
        self::EVENT_CHARGE_FAILED => 'handleChargeFailed',
        self::EVENT_CHARGE_REFUNDED => 'handleChargeRefunded',
    ];

    /**
     * Handle payment intent creation webhook
     *
     * @throws Exception
     */
    protected function handlePaymentIntentCreated(array $payload): void
    {
        try {
            $intent = $payload['data']['object'];
            $failureData = $this->getFailureData($intent['id']);

            if ($order = $this->createOrder($intent, $failureData)) {
                $this->cleanupFailureData($failureData);
            }
        } catch (Exception $e) {
            $this->logError('handlePaymentIntentCreated', $e);
            throw $e;
        }
    }

    /**
     * Handle charge failure webhook
     *
     * @throws Exception
     */
    protected function handleChargeFailed(array $payload): void
    {
        try {
            $charge = $payload['data']['object'];
            $paymentIntentId = $charge['payment_intent'];
            $failureData = [
                'failure_message' => $charge['failure_message'],
                'failure_code' => $charge['failure_code'],
            ];

            if (! $this->updateOrderToFailed($paymentIntentId, $failureData)) {
                $this->storeFailureData($paymentIntentId, $failureData);
            }
        } catch (Exception $e) {
            $this->logError('handleChargeFailed', $e);
            throw $e;
        }
    }

    /**
     * Handle payment intent failure webhook
     *
     * @throws Exception
     */
    protected function handlePaymentIntentFailed(array $payload): void
    {
        try {
            $intent = $payload['data']['object'];
            $failureData = [
                'failure_message' => $intent['last_payment_error']['message'] ?? 'Payment failed',
                'failure_code' => $intent['last_payment_error']['code'] ?? null,
            ];

            if ($order = $this->updateOrderToFailed($intent['id'], $failureData)) {
                $this->logFailedPurchase($order, $failureData['failure_message']);
            }
        } catch (Exception $e) {
            $this->logError('handlePaymentIntentFailed', $e);
            throw $e;
        }
    }

    /**
     * Handle successful checkout completion
     *
     * @throws Exception
     */
    protected function handleCheckoutSessionCompleted(array $payload): mixed
    {
        try {
            $session = $payload['data']['object'];
            $user = User::where('stripe_id', $session['customer'])->first();
            $package = TokenPackage::find($session['metadata']['package_id'] ?? null);

            if ($user && $package) {
                $this->completeOrder($session, $user, $package);
            }

            return response('Webhook Handled', 200);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Handle charge refund webhook
     *
     * @param  array  $payload  The webhook payload from Stripe
     *
     * @throws Exception If there's an error processing the refund
     */
    protected function handleChargeRefunded(array $payload): void
    {
        try {
            $charge = $payload['data']['object'];
            $order = Order::where('payment_id', $charge['payment_intent'])->first();

            if ($order) {
                $order->update([
                    'status' => OrderStatus::REFUNDED,
                    'payment_data' => array_merge($order->payment_data ?? [], [
                        'refund_id' => $charge['refunds']['data'][0]['id'] ?? null,
                        'refund_reason' => $charge['refunds']['data'][0]['reason'] ?? null,
                        'refund_amount' => ($charge['amount_refunded'] ?? 0) / 100,
                    ]),
                ]);

                $order->user->resource(ResourceType::TOKENS)->decrement($order->package->tokens_amount);

                activity('token_purchase')
                    ->performedOn($order->user)
                    ->withProperties([
                        'activity_type' => ActivityType::DECREMENT->value,
                        'package_name' => $order->package->name,
                        'amount' => $order->package->tokens_amount,
                        'price' => $order->amount.' '.strtoupper($order->currency),
                        'resource_type' => Str::title(ResourceType::TOKENS->value),
                        'refund_reason' => $charge['refunds']['data'][0]['reason'] ?? 'No reason provided',
                        ...IdentityProperties::capture(),
                    ])
                    ->log(':properties.resource_type purchase refunded. Package: :properties.package_name.');
            }
        } catch (Exception $e) {
            $this->logError('handleChargeRefunded', $e);
            throw $e;
        }
    }

    /**
     * Order Management Methods
     */
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
                'payment_data' => array_merge($order->payment_data ?? [], $failureData),
            ]);
        }

        return $order;
    }

    private function completeOrder(array $session, User $user, TokenPackage $package): void
    {
        $order = Order::where('payment_id', $session['payment_intent'])->first();

        if ($order) {
            $order->update([
                'status' => OrderStatus::COMPLETED,
                'payment_data' => $session,
            ]);
        }

        $user->resource(ResourceType::TOKENS)->increment($package->tokens_amount);

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

    /**
     * Failure Data Management Methods
     */
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

    /**
     * Logging Methods
     */
    private function logFailedPurchase(Order $order, string $failureReason): void
    {
        activity('token_purchase')
            ->performedOn($order->user)
            ->withProperties([
                'activity_type' => ActivityType::DEFAULT->value,
                'package_name' => $order->package->name,
                'price' => $order->amount.' '.strtoupper($order->currency),
                'resource_type' => Str::title(ResourceType::TOKENS->value),
                'failure_reason' => $failureReason,
                ...IdentityProperties::capture(),
            ])
            ->log(':properties.resource_type purchase failed. Reason: :properties.failure_reason');
    }

    private function logError(string $method, Exception $e): void
    {
        Log::error("Error in {$method}", [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
    }
}
