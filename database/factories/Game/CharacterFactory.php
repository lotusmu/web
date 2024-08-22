<?php

namespace Database\Factories\Game;

use App\Enums\CharacterClass;
use App\Enums\Map;
use App\Enums\PkLevel;
use App\Models\Game\Character;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CharacterFactory extends Factory
{
    protected $model = Character::class;

    public function definition(): array
    {
        $user = User::factory()->create();

        return [
            'AccountID' => $user->name,
            'Name' => substr($this->faker->unique()->userName(), 0, 10),
            'cLevel' => $this->faker->numberBetween(1, 400),
            'LevelUpPoint' => $this->faker->numberBetween(0, 1000),
            'Class' => $this->faker->randomElement(CharacterClass::cases()),
            'Strength' => $this->faker->numberBetween(1, 32767),
            'Dexterity' => $this->faker->numberBetween(1, 32767),
            'Vitality' => $this->faker->numberBetween(1, 32767),
            'Energy' => $this->faker->numberBetween(1, 32767),
            'Leadership' => $this->faker->numberBetween(0, 32767),
            'Money' => $this->faker->numberBetween(0, 2000000000),
            'MapNumber' => $this->faker->randomElement(Map::cases()),
            'MapPosX' => $this->faker->numberBetween(0, 255),
            'MapPosY' => $this->faker->numberBetween(0, 255),
            'PkCount' => $this->faker->numberBetween(0, 100),
            'PkLevel' => $this->faker->randomElement(PkLevel::cases()),
            'PkTime' => $this->faker->numberBetween(0, 1000),
            'CtlCode' => $this->faker->numberBetween(0, 255),
            'ResetCount' => $this->faker->numberBetween(0, 100),
            'MasterResetCount' => $this->faker->numberBetween(0, 10),
            'ExtInventory' => $this->faker->numberBetween(0, 1),
            'Kills' => $this->faker->numberBetween(0, 1000),
            'Deads' => $this->faker->numberBetween(0, 1000),
        ];
    }

    public function newCharacter(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'cLevel' => 1,
                'LevelUpPoint' => 0,
                'Strength' => 20,
                'Dexterity' => 20,
                'Vitality' => 20,
                'Energy' => 20,
                'Leadership' => 0,
                'Money' => 0,
                'MapNumber' => Map::Lorencia,
                'MapPosX' => 125,
                'MapPosY' => 125,
                'PkCount' => 0,
                'PkLevel' => PkLevel::Commoner,
                'PkTime' => 0,
                'ResetCount' => 0,
                'MasterResetCount' => 0,
                'Kills' => 0,
                'Deads' => 0,
            ];
        });
    }

    public function forUser(User $user): static
    {
        return $this->state(function (array $attributes) use ($user) {
            return [
                'AccountID' => $user->name,
            ];
        });
    }
}
