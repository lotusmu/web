<?php

namespace Database\Factories\Payment;

use App\Enums\OrderStatus;
use App\Enums\PaymentProvider;
use App\Models\Payment\Order;
use App\Models\Payment\TokenPackage;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'token_package_id' => TokenPackage::factory(),
            'payment_provider' => $this->faker->randomElement([PaymentProvider::STRIPE, PaymentProvider::PAYPAL]),
            'payment_id' => Str::random(24),
            'amount' => $this->faker->randomFloat(2, 10, 1000),
            'currency' => 'EUR',
            'status' => OrderStatus::PENDING,
            'expires_at' => now()->addMinutes(30),
        ];
    }

    public function completed(): self
    {
        return $this->state(fn (array $attributes) => [
            'status' => OrderStatus::COMPLETED,
        ]);
    }

    public function failed(): self
    {
        return $this->state(fn (array $attributes) => [
            'status' => OrderStatus::FAILED,
        ]);
    }
}
