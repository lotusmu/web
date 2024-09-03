<?php

namespace Database\Factories\Game;

use App\Models\Game\Wallet;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CreditFactory extends Factory
{
    protected $model = Wallet::class;

    public function definition(): array
    {
        $user = User::factory()->create();

        return [
            'AccountID' => $user->name,
            'WCoinC' => $this->faker->numberBetween($min = 0, $max = 5000),
            'zen' => $this->faker->numberBetween($min = 0, $max = 50000000),
        ];
    }
}
