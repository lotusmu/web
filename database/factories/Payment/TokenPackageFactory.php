<?php

namespace Database\Factories\Payment;

use App\Models\Payment\TokenPackage;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TokenPackageFactory extends Factory
{
    protected $model = TokenPackage::class;

    public function definition(): array
    {
        return [
            'stripe_product_id' => 'prod_'.Str::random(14),
            'stripe_price_id' => 'price_'.Str::random(14),
            'name' => $this->faker->numberBetween(100, 10000).' Tokens',
            'tokens_amount' => $this->faker->numberBetween(100, 10000),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'is_popular' => $this->faker->boolean(20),
        ];
    }

    public function popular(): self
    {
        return $this->state(fn (array $attributes) => [
            'is_popular' => true,
        ]);
    }
}
