<?php

use App\Enums\OrderStatus;
use App\Enums\PaymentProvider;
use App\Models\Payment\Order;
use App\Models\Payment\TokenPackage;
use App\Services\Payment\PaymentGatewayFactory;

test('stripe checkout creates session and returns checkout url', function () {
    $package = TokenPackage::factory()->create();
    $gateway = PaymentGatewayFactory::create(PaymentProvider::STRIPE);

    $session = $gateway->initiateCheckout(auth()->user(), $package);

    expect($session)->toHaveProperty('url')
        ->and($session->url)->toContain('checkout.stripe.com');
});

test('stripe webhook handles successful payment', function () {
    $order = Order::factory()->create([
        'status' => OrderStatus::PENDING,
        'payment_provider' => PaymentProvider::STRIPE,
    ]);

    $response = $this->postJson(route('checkout.webhook.stripe'), [
        'type' => 'checkout.session.completed',
        'data' => [
            'object' => [
                'payment_intent' => $order->payment_id,
                'customer' => auth()->user()->stripe_id,
                'amount_total' => $order->amount * 100,
                'currency' => strtolower($order->currency),
                'metadata' => ['package_id' => $order->token_package_id],
            ],
        ],
    ]);

    $response->assertSuccessful();
    expect($order->fresh()->status)->toBe(OrderStatus::COMPLETED);
});

test('stripe handles failed payments', function () {
    $order = Order::factory()->create([
        'status' => OrderStatus::PENDING,
        'payment_provider' => PaymentProvider::STRIPE,
    ]);

    $response = $this->postJson(route('checkout.webhook.stripe'), [
        'type' => 'payment_intent.payment_failed',
        'data' => [
            'object' => [
                'id' => $order->payment_id,
                'last_payment_error' => [
                    'message' => 'Card declined',
                    'code' => 'card_declined',
                ],
            ],
        ],
    ]);

    $response->assertSuccessful();
    expect($order->fresh()->status)->toBe(OrderStatus::FAILED)
        ->and($order->fresh()->payment_data)->toHaveKey('failure_message');
});
