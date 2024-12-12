<?php

namespace Database\Factories\Game;

use App\Enums\Game\GuildMemberStatus;
use App\Models\Game\Character;
use App\Models\Game\Guild;
use App\Models\Game\GuildMember;
use Illuminate\Database\Eloquent\Factories\Factory;

class GuildMemberFactory extends Factory
{
    protected $model = GuildMember::class;

    public function definition(): array
    {
        return [
            'Name' => Character::factory(),
            'G_Name' => Guild::factory(),
            'G_Level' => 0,  // Default level for regular members
            'G_Status' => GuildMemberStatus::GuildMember,
        ];
    }

    public function forGuild(Guild $guild): static
    {
        return $this->state(fn (array $attributes) => [
            'G_Name' => $guild->G_Name,
        ]);
    }

    public function forCharacter(Character $character): static
    {
        return $this->state(fn (array $attributes) => [
            'Name' => $character->Name,
        ]);
    }

    public function guildMaster(): static
    {
        return $this->state(fn (array $attributes) => [
            'G_Status' => GuildMemberStatus::GuildMaster,
            'G_Level' => 1,  // As per stored procedure
        ]);
    }

    public function assistantMaster(): static
    {
        return $this->state(fn (array $attributes) => [
            'G_Status' => GuildMemberStatus::AssistantGuildMaster,
            'G_Level' => 0,
        ]);
    }

    public function battleMaster(): static
    {
        return $this->state(fn (array $attributes) => [
            'G_Status' => GuildMemberStatus::BattleMaster,
            'G_Level' => 0,
        ]);
    }
}
