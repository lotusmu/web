<?php

namespace App\Services\Payment;

use App\Enums\PaymentProvider;
use App\Interfaces\PaymentGateway;
use InvalidArgumentException;

class PaymentGatewayFactory
{
    private static array $gateways = [
        PaymentProvider::STRIPE->value => StripeGateway::class,
        PaymentProvider::PAYPAL->value => PayPalGateway::class,
    ];

    public static function create(PaymentProvider|string $provider): PaymentGateway
    {
        $provider = $provider instanceof PaymentProvider ? $provider->value : $provider;

        if (! isset(self::$gateways[$provider])) {
            throw new InvalidArgumentException("Unsupported payment provider: {$provider}");
        }

        $gatewayClass = self::$gateways[$provider];

        return new $gatewayClass;
    }

    public static function registerGateway(string $provider, string $gatewayClass): void
    {
        if (! is_subclass_of($gatewayClass, PaymentGateway::class)) {
            throw new InvalidArgumentException('Gateway must implement PaymentGateway interface');
        }

        self::$gateways[$provider] = $gatewayClass;
    }
}
