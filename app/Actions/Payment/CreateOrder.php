<?php

namespace App\Actions\Payment;

use App\Enums\OrderStatus;
use App\Enums\PaymentProvider;
use App\Models\Payment\Order;
use App\Models\Payment\TokenPackage;
use App\Models\User\User;
use Illuminate\Support\Str;

class CreateOrder
{
    private const EXPIRATION_MINUTES = 30;

    private const PROVIDER_PREFIXES = [
        PaymentProvider::STRIPE->value => 'st_',
        PaymentProvider::PAYPAL->value => 'pp_',
        PaymentProvider::PRIME->value => 'prime_',
    ];

    public function handle(
        User $user,
        TokenPackage $package,
        PaymentProvider $provider,
        ?string $paymentId = null
    ): Order {
        return Order::firstOrCreate(
            [
                'user_id' => $user->id,
                'token_package_id' => $package->id,
                'status' => OrderStatus::PENDING,
            ],
            [
                'payment_provider' => $provider,
                'payment_id' => $paymentId ?? $this->generatePaymentId($provider),
                'amount' => $package->price,
                'currency' => config('app.currency', 'EUR'),
                'expires_at' => now()->addMinutes(self::EXPIRATION_MINUTES),
            ]
        );
    }

    private function generatePaymentId(PaymentProvider $provider): string
    {
        $prefix = self::PROVIDER_PREFIXES[$provider->value] ?? '';

        return $prefix.Str::random(20);
    }
}
