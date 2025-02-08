<?php

namespace App\Actions\Rankings;

use App\Actions\Wallet\IncrementResource;
use App\Enums\Utility\ActivityType;
use App\Enums\Utility\RankingScoreType;
use App\Enums\Utility\ResourceType;
use App\Models\Game\Character;
use App\Models\Game\Ranking\WeeklyRankingArchive;
use App\Models\Game\Ranking\WeeklyRankingConfiguration;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessRankingType
{
    public function __construct(
        private readonly WeeklyRankingConfiguration $config,
        private readonly RankingScoreType $type,
        private readonly Carbon $cycleStart,
        private readonly Carbon $cycleEnd,
    ) {}

    public function handle(): void
    {
        $topPlayers = $this->getTopPlayers();

        foreach ($topPlayers as $rank => $player) {
            $rewards = $this->getRewardsForRank($rank + 1);

            $this->archiveRanking($player, $rank + 1, $rewards);

            $this->giveRewards($player, $rewards, $rank + 1);
        }

        Log::info('Rankings processed', [
            'server' => $this->config->server->name,
            'type' => $this->type->value,
            'players_rewarded' => $topPlayers->count(),
            'cycle_end' => $this->cycleEnd->format('Y-m-d H:i:s'),
        ]);

        $this->resetScores();
    }

    private function getTopPlayers()
    {
        $scoreField = $this->type->weeklyScoreField();

        $maxPosition = $this->config->rewards()
            ->max('position_to');

        return Character::on($this->config->server->connection_name)
            ->orderByDesc($scoreField)
            ->limit($maxPosition)
            ->get();
    }

    private function getRewardsForRank(int $position)
    {
        return $this->config->rewards()
            ->where('position_from', '<=', $position)
            ->where('position_to', '>=', $position)
            ->first()?->rewards ?? [];
    }

    private function archiveRanking(Character $character, int $rank, array $rewards): void
    {
        WeeklyRankingArchive::create([
            'game_server_id' => $this->config->game_server_id,
            'type' => $this->type,
            'cycle_start' => $this->cycleStart,
            'cycle_end' => $this->cycleEnd,
            'rank' => $rank,
            'character_name' => $character->Name,
            'score' => $character->{$this->type->weeklyScoreField()},
            'rewards_given' => $rewards,
        ]);
    }

    private function giveRewards(Character $character, array $rewards, int $rank): void
    {
        if (empty($rewards)) {
            return;
        }

        $user = Character::findUserByCharacterName($character->Name);

        if (! $user) {
            return;
        }

        foreach ($rewards as $reward) {
            $resourceType = ResourceType::from($reward['type']);

            (new IncrementResource(
                user: $user,
                resourceType: $resourceType,
                amount: (int) $reward['amount']
            ))->handle();

            $this->logRewardDistribution($user, $character, $rank, $resourceType, $reward['amount']);
        }
    }

    private function format(int $amount): string
    {
        return number_format($amount);
    }

    private function logRewardDistribution($user, $character, $rank, $resourceType, $amount): void
    {
        activity('weekly_ranking_reward')
            ->performedOn($user)
            ->withProperties([
                'activity_type' => ActivityType::INCREMENT->value,
                'character' => $character->Name,
                'rank' => $rank,
                'score' => $character->{$this->type->weeklyScoreField()},
                'reward_type' => $resourceType->value,
                'reward_amount' => $this->format($amount),
                'ranking_type' => $this->type->label(),
                'server' => $this->config->server->name,
                'cycle_end' => $this->cycleEnd->format('Y-m-d'),
            ])
            ->log("Received weekly {$this->type->label()} ranking reward for rank #{$rank} ({$resourceType->value}).");
    }

    private function resetScores(): void
    {
        Character::on($this->config->server->connection_name)
            ->where($this->type->weeklyScoreField(), '>', 0)
            ->update([$this->type->weeklyScoreField() => 0]);

        DB::connection($this->config->server->connection_name)
            ->table($this->type === RankingScoreType::EVENTS ? 'RankingEventsWeekly' : 'RankingHuntersWeekly')
            ->truncate();
    }
}
