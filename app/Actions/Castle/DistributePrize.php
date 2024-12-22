<?php

namespace App\Actions\Castle;

use App\Enums\Utility\ActivityType;
use App\Enums\Utility\ResourceType;
use App\Models\Game\CastleData;
use App\Models\Game\Guild;
use App\Models\User\User;
use App\Models\Utility\CastlePrize;
use App\Models\Utility\CastlePrizeDistribution;
use App\Support\ActivityLog\IdentityProperties;

readonly class DistributePrize
{
    public function __construct(
        private CastleData $castle,
        private CastlePrize $prizeSetting,
        private int $amount
    ) {}

    public function handle(): bool
    {
        $connection = $this->prizeSetting->gameServer->connection_name;
        session(['game_db_connection' => $connection]);

        $winningGuild = Guild::where('G_Name', $this->castle->OWNER_GUILD)->first();

        if (! $winningGuild) {
            return false;
        }

        $guildMembers = $winningGuild->members()
            ->with('character')
            ->get();

        $accountIds = $guildMembers->map(function ($member) {
            return $member->character->AccountID ?? null;
        })->filter()->unique();

        $users = User::with(['member.wallet'])
            ->whereIn('name', $accountIds)
            ->get()
            ->keyBy('name');

        if ($users->isEmpty()) {
            return false;
        }

        $amountPerMember = (int) floor($this->amount / $users->count());
        $distributed = false;

        foreach ($users as $user) {
            $user->resource(ResourceType::CREDITS)->increment($amountPerMember);
            $this->recordActivity($user, $amountPerMember, $winningGuild->G_Name);
            $distributed = true;
        }

        if ($distributed) {
            $this->recordDistribution($winningGuild->G_Name, $users->count(), $amountPerMember);
        }

        return $distributed;
    }

    private function recordActivity(User $user, int $amount, string $guildName): void
    {
        $balance = $user->getResourceValue(ResourceType::CREDITS);

        $properties = [
            'activity_type' => ActivityType::INCREMENT->value,
            'guild_name' => $guildName,
            'amount' => number_format($amount),
            'balance' => number_format($balance),
            ...IdentityProperties::capture(),
        ];

        activity('castle_siege')
            ->performedOn($user)
            ->withProperties($properties)
            ->log('Castle Siege credits reward received.');
    }

    private function recordDistribution(string $guildName, int $totalMembers, int $amountPerMember): void
    {
        CastlePrizeDistribution::create([
            'castle_prize_id' => $this->prizeSetting->id,
            'guild_name' => $guildName,
            'total_members' => $totalMembers,
            'distributed_amount' => $this->amount,
            'amount_per_member' => $amountPerMember,
        ]);
    }
}
