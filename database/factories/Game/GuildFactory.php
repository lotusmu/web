<?php

namespace Database\Factories\Game;

use App\Models\Game\Character;
use App\Models\Game\Guild;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

class GuildFactory extends Factory
{
    protected $model = Guild::class;

    public function definition(): array
    {
        $master = Character::factory()->create();

        return [
            'G_Name' => substr(fake()->unique()->word(), 0, 8),
            'G_Mark' => DB::raw('CONVERT(varbinary(32), 0x0000000000000000000000000000000000000000000000000000000000000000)'),
            'G_Score' => fake()->numberBetween(0, 100000),
            'G_Master' => $master->Name,
            'G_Count' => fake()->numberBetween(1, 80),
            'G_Notice' => fake()->text(60),
            'G_Type' => fake()->numberBetween(0, 1),
            'G_Rival' => 0,
            'G_Union' => 0,
            'MemberCount' => fake()->numberBetween(1, 80),
            'CS_Wins' => fake()->numberBetween(0, 100),
        ];
    }
}
