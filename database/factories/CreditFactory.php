<?php

namespace Database\Factories;

use App\Models\Game\Credit;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CreditFactory extends Factory
{
    protected $model = Credit::class;

    public function definition(): array
    {
        $user = User::factory()->create();

        return [
            'AccountID' => $user->name,
            'WCoinC' => $this->faker->numberBetween($min = 0, $max = 5000),
        ];
    }
}
