<?php

namespace Database\Factories\Game;

use App\Models\Game\Wallet;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class WalletFactory extends Factory
{
    protected $model = Wallet::class;

    public function definition(): array
    {
        return [
            'AccountID' => User::factory(),
            'WCoinC' => $this->faker->numberBetween($min = 0, $max = 5000),
            'zen' => $this->faker->numberBetween($min = 0, $max = 50000000),
        ];
    }

    public function withExistingUser(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'AccountID' => $user->name,
        ]);
    }
}
