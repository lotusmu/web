<?php

namespace Database\Factories\Utility;

use App\Models\Utility\GameServer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class GameServerFactory extends Factory
{
    protected $model = GameServer::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'connection_name' => $this->faker->unique()->word,
            'experience_rate' => $this->faker->randomDigit(),
            'drop_rate' => $this->faker->randomDigit(),
            'is_active' => $this->faker->boolean(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
