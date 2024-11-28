<?php

namespace App\Services\Payment;

use App\Enums\OrderStatus;
use App\Enums\PaymentProvider;
use App\Enums\Utility\ResourceType;
use App\Interfaces\PaymentGateway;
use App\Models\Payment\Order;
use App\Models\Payment\TokenPackage;
use App\Models\User\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PrimeGateway implements PaymentGateway
{
    private const API_URL = 'https://pay.primepayments.io/API/v2/';

    public function initiateCheckout(User $user, TokenPackage $package): mixed
    {
        $order = Order::firstOrCreate(
            [
                'user_id' => $user->id,
                'token_package_id' => $package->id,
                'status' => OrderStatus::PENDING,
            ],
            [
                'payment_provider' => PaymentProvider::PRIME,
                'payment_id' => 'prime_'.Str::random(20),
                'amount' => $package->price,
                'currency' => 'EUR',
                'expires_at' => now()->addMinutes(30),
            ]
        );

        try {
            $requestData = [
                'action' => 'initPayment',
                'project' => config('services.prime.project_id'),
                'sum' => $package->price,
                'currency' => 'EUR',
                'innerID' => $order->id,
                'email' => $user->email,
                'success_url' => route('checkout.prime.success', $order),
                'cancel_url' => route('checkout.prime.cancel', $order),
                'lang' => 'EN',
            ];

            $requestData['sign'] = md5(
                config('services.prime.secret1').
                $requestData['action'].
                $requestData['project'].
                $requestData['sum'].
                $requestData['currency'].
                $requestData['innerID'].
                $requestData['email']
            );

            $response = Http::asForm()->post(self::API_URL, $requestData);

            if (! $response->successful()) {
                throw new Exception('Prime payment initialization failed: '.$response->body());
            }

            $data = $response->json();

            if ($data['status'] !== 'OK') {
                throw new Exception($data['result'] ?? 'Prime payment error');
            }

            return $data['result'];

        } catch (Exception $e) {
            Log::error('Prime payment initiation error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $requestData ?? null,
                'response' => $data ?? null,
            ]);
            throw $e;
        }
    }

    public function handleWebhook(array $payload): mixed
    {
        try {
            return match ($payload['action']) {
                'order_payed' => $this->handlePaymentSuccess($payload),
                'order_cancel' => $this->handlePaymentCancel($payload),
                default => null,
            };
        } catch (Exception $e) {
            Log::error('Prime webhook error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'payload' => $payload,
            ]);
            throw $e;
        }
    }

    public function processOrder(Order $order): bool
    {
        try {
            $response = Http::post(self::API_URL, [
                'action' => 'getOrderInfo',
                'project' => config('services.prime.project_id'),
                'orderID' => $order->payment_id,
                'sign' => $this->calculateOrderInfoSign($order->payment_id),
            ]);

            if (! $response->successful()) {
                return false;
            }

            $data = $response->json();
            if ($data['status'] !== 'OK') {
                return false;
            }

            $status = $data['result']['pay_status'] ?? null;

            return $this->updateOrderStatus($order, $status, $data['result']);
        } catch (Exception $e) {
            Log::error('Prime process order error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'order' => $order->id,
            ]);

            return false;
        }
    }

    public function verifyWebhookSignature(string $payload, array $headers): bool
    {
        try {
            $data = json_decode($payload, true);

            $expectedSign = match ($data['action']) {
                'order_payed' => md5(
                    config('services.prime.secret2').
                    $data['orderID'].
                    $data['payWay'].
                    $data['innerID'].
                    $data['sum'].
                    $data['webmaster_profit']
                ),
                'order_cancel' => md5(
                    config('services.prime.secret2').
                    $data['orderID'].
                    $data['innerID']
                ),
                default => null
            };

            return $expectedSign && $expectedSign === ($data['sign'] ?? '');

        } catch (Exception $e) {
            Log::error('Prime signature verification error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

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
            Log::error('Prime cancel order error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'order' => $order->id,
            ]);

            return false;
        }
    }

    private function handlePaymentSuccess(array $payload): mixed
    {
        $order = Order::where('payment_id', $payload['orderID'])
            ->where('status', OrderStatus::PENDING)
            ->first();

        if (! $order) {
            return null;
        }

        DB::transaction(function () use ($order, $payload) {
            $order->update([
                'status' => OrderStatus::COMPLETED,
                'payment_data' => [
                    'payed_from' => $payload['payed_from'] ?? null,
                    'webmaster_profit' => $payload['webmaster_profit'],
                    'date_pay' => $payload['date_pay'],
                    'payway' => $payload['payWay'],
                ],
            ]);

            $order->user->resource(ResourceType::TOKENS)
                ->increment($order->package->tokens_amount);
        });

        return null;
    }

    private function handlePaymentCancel(array $payload): mixed
    {
        $order = Order::where('payment_id', $payload['orderID'])
            ->where('status', OrderStatus::PENDING)
            ->first();

        if (! $order) {
            return null;
        }

        $order->update([
            'status' => OrderStatus::FAILED,
            'payment_data' => [
                'payed_from' => $payload['payed_from'] ?? null,
                'date_pay' => $payload['date_pay'],
                'failure_reason' => 'Payment cancelled',
            ],
        ]);

        return null;
    }

    private function calculateOrderInfoSign(string $orderId): string
    {
        return md5(
            config('services.prime.secret1').
            'getOrderInfo'.
            config('services.prime.project_id').
            $orderId
        );
    }

    private function updateOrderStatus(Order $order, ?string $status, array $data): bool
    {
        $newStatus = match ($status) {
            '1' => OrderStatus::COMPLETED,
            '-1' => OrderStatus::FAILED,
            '-2' => OrderStatus::EXPIRED,
            '3' => OrderStatus::REFUNDED,
            default => null
        };

        if (! $newStatus || $order->status === $newStatus) {
            return false;
        }

        DB::transaction(function () use ($order, $newStatus, $data) {
            $order->update([
                'status' => $newStatus,
                'payment_data' => [
                    'payed_from' => $data['payed_from'] ?? null,
                    'webmaster_profit' => $data['webmaster_profit'] ?? null,
                    'date_add' => $data['date_add'],
                    'payway' => $data['payWay'],
                ],
            ]);

            if ($newStatus === OrderStatus::COMPLETED) {
                $order->user->resource(ResourceType::TOKENS)
                    ->increment($order->package->tokens_amount);
            }
        });

        return true;
    }
}
