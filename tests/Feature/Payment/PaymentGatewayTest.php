<?php

use App\Enums\PaymentProvider;
use App\Interfaces\PaymentGateway;
use App\Services\Payment\PaymentGatewayFactory;
use App\Services\Payment\PayPalGateway;
use App\Services\Payment\StripeGateway;

test('payment gateway factory creates correct gateway instance', function () {
    $stripeGateway = PaymentGatewayFactory::create(PaymentProvider::STRIPE);
    $paypalGateway = PaymentGatewayFactory::create(PaymentProvider::PAYPAL);

    expect($stripeGateway)->toBeInstanceOf(StripeGateway::class)
        ->and($paypalGateway)->toBeInstanceOf(PayPalGateway::class);
});

test('payment gateway factory throws exception for invalid provider', function () {
    PaymentGatewayFactory::create('invalid');
})->throws(InvalidArgumentException::class);

test('can register new gateway dynamically', function () {
    $customGateway = new class implements PaymentGateway
    {
        public function initiateCheckout($user, $package): mixed
        {
            return null;
        }

        public function handleWebhook(array $payload): mixed
        {
            return null;
        }

        public function processOrder($order): bool
        {
            return true;
        }

        public function verifyWebhookSignature($payload, array $headers): bool
        {
            return true;
        }

        public function cancelOrder($order): bool
        {
            return true;
        }
    };

    PaymentGatewayFactory::registerGateway('custom', get_class($customGateway));
    expect(PaymentGatewayFactory::create('custom'))->toBeInstanceOf(get_class($customGateway));
});

test('registering invalid gateway throws exception', function () {
    PaymentGatewayFactory::registerGateway('invalid', stdClass::class);
})->throws(InvalidArgumentException::class);
