<?php

namespace App\Services\Payment\Prime;

use App\Enums\OrderStatus;
use App\Enums\PaymentProvider;
use App\Models\Payment\Order;
use App\Models\Payment\TokenPackage;
use App\Models\User\User;
use App\Services\Payment\BasePaymentGateway;
use Exception;
use Illuminate\Support\Facades\Http;

class PrimeGateway extends BasePaymentGateway
{
    private const API_URL = 'https://pay.primepayments.io/API/v2/';

    private const EVENT_PAYMENT_SUCCESS = 'order_payed';

    private const EVENT_PAYMENT_CANCEL = 'order_cancel';

    public function getProviderName(): string
    {
        return 'Prime';
    }

    public function initiateCheckout(User $user, TokenPackage $package): mixed
    {
        try {
            $order = $this->createOrder->handle(
                user: $user,
                package: $package,
                provider: PaymentProvider::PRIME
            );

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
            $this->logError($e, 'initiateCheckout', [
                'user' => $user->id,
                'package' => $package->id,
                'request' => $requestData ?? null,
                'response' => $data ?? null,
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
            $newStatus = match ($status) {
                '1' => OrderStatus::COMPLETED,
                '-1' => OrderStatus::FAILED,
                '-2' => OrderStatus::EXPIRED,
                '3' => OrderStatus::REFUNDED,
                default => null
            };

            if (! $newStatus) {
                return false;
            }

            $paymentData = [
                'payed_from' => $data['result']['payed_from'] ?? null,
                'webmaster_profit' => $data['result']['webmaster_profit'] ?? null,
                'date_add' => $data['result']['date_add'],
                'payway' => $data['result']['payWay'],
            ];

            if ($newStatus === OrderStatus::FAILED) {
                $paymentData['failure_reason'] = $data['result']['failure_reason'] ?? 'Payment processing failed';
            }

            return $this->updateOrderStatus->handle(
                order: $order,
                newStatus: $newStatus,
                paymentData: $paymentData
            );

        } catch (Exception $e) {
            $this->logError($e, 'processOrder', ['order' => $order->id]);

            return false;
        }
    }

    public function handleWebhook(array $payload): mixed
    {
        try {
            $webhookId = $payload['orderID'] ?? '';
    
            return $this->processWebhookWithLock($webhookId, function () use ($payload) {
                return match ($payload['action'] ?? '') {
                    self::EVENT_PAYMENT_SUCCESS => $this->handlePaymentSuccess($payload),
                    self::EVENT_PAYMENT_CANCEL => $this->handlePaymentCancel($payload),
                    default => null,
                };
            });
        } catch (Exception $e) {
            $this->logError($e, 'handleWebhook');
            throw $e;
        }
    }

    public function verifyWebhookSignature(array $data, array $headers): bool
    {
        try {
            $expectedSign = match ($data['action'] ?? '') {
                self::EVENT_PAYMENT_SUCCESS => md5(
                    config('services.prime.secret2') .
                    $data['orderID'] .
                    $data['payWay'] .
                    $data['innerID'] .
                    $data['sum'] .
                    $data['webmaster_profit']
                ),
                self::EVENT_PAYMENT_CANCEL => md5(
                    config('services.prime.secret2') .
                    $data['orderID'] .
                    $data['innerID']
                ),
                default => null
            };

            return $expectedSign && $expectedSign === ($data['sign'] ?? '');

        } catch (Exception $e) {
            $this->logError($e, 'verifyWebhookSignature');

            return false;
        }
    }

    private function handlePaymentSuccess(array $payload): mixed
    {
        $order = Order::where('id', $payload['innerID'])
            ->where('status', OrderStatus::PENDING)
            ->first();

        if (! $order) {
            return null;
        }

        return $this->updateOrderStatus->handle(
            order: $order,
            newStatus: OrderStatus::COMPLETED,
            paymentData: [
                'payed_from' => $payload['payed_from'] ?? null,
                'webmaster_profit' => $payload['webmaster_profit'],
                'date_pay' => $payload['date_pay'],
                'payway' => $payload['payWay'],
                'currency' => $payload['currency'],
                'sum' => $payload['sum'],
            ]
        );
    }

    private function handlePaymentCancel(array $payload): mixed
    {
        $order = Order::where('id', $payload['innerID'])
            ->where('status', OrderStatus::PENDING)
            ->first();

        if (! $order) {
            return null;
        }

        return $this->updateOrderStatus->handle(
            order: $order,
            newStatus: OrderStatus::FAILED,
            paymentData: [
                'payed_from' => $payload['payed_from'] ?? null,
                'date_pay' => $payload['date_pay'],
                'currency' => $payload['currency'],
                'sum' => $payload['sum'],
                'failure_reason' => 'Payment cancelled by user or system',
            ]
        );
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
}
