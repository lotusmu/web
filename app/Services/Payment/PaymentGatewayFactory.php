<?php

namespace App\Services\Payment;

use App\Actions\Payment\CreateOrder;
use App\Actions\Payment\UpdateOrderStatus;
use App\Enums\PaymentProvider;
use App\Interfaces\PaymentGateway;
use App\Services\Payment\PayPal\PayPalClient;
use App\Services\Payment\PayPal\PayPalGateway;
use App\Services\Payment\Prime\PrimeGateway;
use App\Services\Payment\Stripe\StripeGateway;
use InvalidArgumentException;

class PaymentGatewayFactory
{
    private static array $gateways = [
        PaymentProvider::STRIPE->value => StripeGateway::class,
        PaymentProvider::PAYPAL->value => PayPalGateway::class,
        PaymentProvider::PRIME->value => PrimeGateway::class,
    ];

    private static array $requiredConfig = [
        PaymentProvider::STRIPE->value => [
            'cashier.key',
            'cashier.secret',
            'cashier.webhook.secret',
        ],
        PaymentProvider::PAYPAL->value => [
            'services.paypal.client_id',
            'services.paypal.secret',
            'services.paypal.webhook_id',
            'services.paypal.mode',
        ],
        PaymentProvider::PRIME->value => [
            'services.prime.project_id',
            'services.prime.secret1',
            'services.prime.secret2',
        ],
    ];

    public static function create(PaymentProvider|string $provider): PaymentGateway
    {
        $provider = $provider instanceof PaymentProvider ? $provider->value : $provider;

        self::validateProviderConfig($provider);

        if (! isset(self::$gateways[$provider])) {
            throw new InvalidArgumentException("Unsupported payment provider: {$provider}");
        }

        $gatewayClass = self::$gateways[$provider];

        // Base dependencies that all gateways need
        $baseServices = [
            'createOrder' => app(CreateOrder::class),
            'updateOrderStatus' => app(UpdateOrderStatus::class),
        ];

        // Add provider-specific dependencies
        return match ($provider) {
            PaymentProvider::PAYPAL->value => app()->make($gatewayClass, [
                ...$baseServices,
                'client' => app(PayPalClient::class),
            ]),
            PaymentProvider::STRIPE->value => app()->make($gatewayClass, $baseServices),
            PaymentProvider::PRIME->value => app()->make($gatewayClass, $baseServices),
            default => throw new InvalidArgumentException("Unsupported payment provider: {$provider}")
        };
    }

    public static function registerGateway(string $provider, string $gatewayClass): void
    {
        if (! is_subclass_of($gatewayClass, PaymentGateway::class)) {
            throw new InvalidArgumentException('Gateway must implement PaymentGateway interface');
        }

        self::$gateways[$provider] = $gatewayClass;
    }

    private static function validateProviderConfig(string $provider): void
    {
        foreach (self::$requiredConfig[$provider] ?? [] as $config) {
            if (empty(config($config))) {
                throw new InvalidArgumentException("Missing required configuration: {$config}");
            }
        }
    }
}
