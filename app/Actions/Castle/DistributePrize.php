<?php

namespace App\Actions\Castle;

use App\Enums\Utility\ActivityType;
use App\Enums\Utility\ResourceType;
use App\Models\Game\CastleData;
use App\Models\Game\Guild;
use App\Models\User\User;
use App\Models\Utility\CastlePrize;
use App\Models\Utility\CastlePrizeDistribution;
use App\Models\Utility\GameServer;
use App\Support\ActivityLog\IdentityProperties;
use Illuminate\Support\Collection;

readonly class DistributePrize
{
    private string $connection;

    public function __construct(
        private CastleData $castle,
        private CastlePrize $prizeSetting,
        private int $amount
    ) {
        $this->connection = $prizeSetting->gameServer->connection_name;
    }

    public function handle(): bool
    {
        session(['game_db_connection' => $this->connection]);

        $winningGuild = $this->getWinningGuild();
        if (! $winningGuild) {
            return false;
        }

        $users = $this->getEligibleUsers($winningGuild);
        if ($users->isEmpty()) {
            return false;
        }

        return $this->distributeRewards($users, $winningGuild);
    }

    private function getWinningGuild(): ?Guild
    {
        return Guild::where('G_Name', $this->castle->OWNER_GUILD)->first();
    }

    private function getEligibleUsers(Guild $guild): Collection
    {
        $guildMembers = $guild->members()
            ->with('character')
            ->get();

        $accountIds = $guildMembers->map(function ($member) {
            return $member->character->AccountID ?? null;
        })->filter()->unique();

        return User::with(['member.wallet'])
            ->whereIn('name', $accountIds)
            ->get()
            ->keyBy('name');
    }

    private function distributeRewards(Collection $users, Guild $guild): bool
    {
        $amountPerMember = (int) floor($this->amount / $users->count());
        $distributed = false;

        foreach ($users as $user) {
            $user->resource(ResourceType::CREDITS)->increment($amountPerMember);
            $this->recordActivity($user, $amountPerMember, $guild->G_Name);
            $distributed = true;
        }

        if ($distributed) {
            $this->recordDistribution($guild->G_Name, $users->count(), $amountPerMember);
        }

        return $distributed;
    }

    private function recordActivity(User $user, int $amount, string $guildName): void
    {
        $balance = $user->getResourceValue(ResourceType::CREDITS);

        $serverName = GameServer::where('connection_name', session('game_db_connection', 'gamedb_main'))
            ->first()
            ->getServerName();

        $properties = [
            'activity_type' => ActivityType::INCREMENT->value,
            'guild_name' => $guildName,
            'amount' => number_format($amount),
            'balance' => number_format($balance),
            'connection' => $serverName,
            ...IdentityProperties::capture(),
        ];

        activity('castle_siege')
            ->performedOn($user)
            ->withProperties($properties)
            ->log('Castle Siege credits reward received (:properties.connection).');
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
