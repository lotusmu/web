<?php

use App\Enums\OrderStatus;
use App\Enums\PaymentProvider;
use App\Models\Payment\Order;
use App\Models\Payment\TokenPackage;
use App\Services\Payment\PaymentGatewayFactory;
use App\Services\Payment\PayPal\PayPalGateway;
use Illuminate\Support\Facades\Http;

test('paypal checkout creates pending order and returns checkout url', function () {
    Http::fake([
        'api-m.sandbox.paypal.com/v2/checkout/orders' => Http::response([
            'id' => 'TEST_ORDER_ID',
            'links' => [
                ['rel' => 'self', 'href' => 'https://api.sandbox.paypal.com/v2/checkout/orders/TEST_ORDER_ID'],
                ['rel' => 'approve', 'href' => 'https://www.sandbox.paypal.com/checkoutnow/approve/TEST_ORDER_ID'],
            ],
        ], 200),
    ]);

    $package = TokenPackage::factory()->create();
    $gateway = PaymentGatewayFactory::create(PaymentProvider::PAYPAL);

    $checkoutUrl = $gateway->initiateCheckout(auth()->user(), $package);

    expect($checkoutUrl)->toContain('sandbox.paypal.com')
        ->and(Order::where('payment_provider', PaymentProvider::PAYPAL)
            ->where('status', OrderStatus::PENDING)
            ->exists())->toBeTrue();
});

test('paypal webhook verifies signature and processes payment', function () {
    $mockPayPalGateway = Mockery::mock(PayPalGateway::class);
    $mockPayPalGateway->shouldReceive('verifyWebhookSignature')->andReturn(true);
    $mockPayPalGateway->shouldReceive('handleWebhook')->once();

    $mockFactory = Mockery::mock(PaymentGatewayFactory::class);
    $mockFactory->shouldReceive('create')->with(PaymentProvider::PAYPAL)->andReturn($mockPayPalGateway);

    $this->instance(PaymentGatewayFactory::class, $mockFactory);

    $order = Order::factory()->create([
        'status' => OrderStatus::PENDING,
        'payment_provider' => PaymentProvider::PAYPAL,
    ]);

    $response = $this->postJson(route('checkout.paypal.webhook'), [
        'event_type' => 'PAYMENT.CAPTURE.COMPLETED',
        'resource' => [
            'id' => $order->payment_id,
            'status' => 'COMPLETED',
            'amount' => ['value' => $order->amount, 'currency_code' => $order->currency],
        ],
    ]);

    $response->assertSuccessful();
});

test('paypal payment can be cancelled', function () {
    $order = Order::factory()->create([
        'status' => OrderStatus::PENDING,
        'payment_provider' => PaymentProvider::PAYPAL,
    ]);

    $response = $this->get(route('checkout.paypal.cancel', $order));

    $response->assertRedirect(route('donate'))
        ->assertSessionHas('toast');

    expect($order->fresh()->status)->toBe(OrderStatus::CANCELLED);
});

test('paypal webhook rejects invalid signature', function () {
    $mockFactory = Mockery::mock(PaymentGatewayFactory::class);
    $mockFactory->shouldReceive('create')
        ->with(PaymentProvider::PAYPAL)
        ->andReturn(tap(Mockery::mock(PayPalGateway::class), function ($mock) {
            $mock->shouldReceive('verifyWebhookSignature')->andReturn(false);
        }));

    $this->instance(PaymentGatewayFactory::class, $mockFactory);

    $response = $this->postJson(route('checkout.paypal.webhook'), ['event_type' => 'TEST']);
    $response->assertStatus(400);
});

test('expired paypal orders are handled correctly', function () {
    $order = Order::factory()->create([
        'status' => OrderStatus::PENDING,
        'payment_provider' => PaymentProvider::PAYPAL,
        'expires_at' => now()->subMinutes(5),
    ]);

    $response = $this->get(route('checkout.paypal.process', $order));
    $response->assertRedirect(route('donate'));
    expect($order->fresh()->status)->toBe(OrderStatus::EXPIRED);
});
