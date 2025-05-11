<?php

namespace App\Actions\Rankings;

use App\Actions\User\SendNotification;
use App\Actions\Wallet\IncrementResource;
use App\Enums\Utility\ActivityType;
use App\Enums\Utility\RankingLogStatus;
use App\Enums\Utility\RankingScoreType;
use App\Enums\Utility\ResourceType;
use App\Models\Game\Character;
use App\Models\Game\Ranking\WeeklyRankingArchive;
use App\Models\Game\Ranking\WeeklyRankingConfiguration;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
        $rankings = $this->getTopPlayers()
            ->map(fn (Character $player, int $index) => [
                'player' => $player,
                'rank' => $index + 1,
                'rewards' => $this->getRewardsForRank($index + 1),
            ]);

        DB::connection($this->config->server->connection_name)->transaction(function () use ($rankings) {
            foreach ($rankings as $ranking) {
                $this->processRanking(
                    character: $ranking['player'],
                    rank: $ranking['rank'],
                    rewards: $ranking['rewards']
                );
            }

            $this->resetScores();
        });

        activity('weekly_rankings')
            ->event('process')
            ->withProperties([
                'server' => $this->config->server->name,
                'type' => $this->type->value,
                'status' => RankingLogStatus::SUCCESS,
                'players_count' => $rankings->count(),
            ])
            ->log("Rankings type {$this->type->value} processed successfully");
    }

    private function getTopPlayers()
    {
        $scoreField = $this->type->weeklyScoreField();
        $maxPosition = $this->config->rewards()->max('position_to');

        return Character::on($this->config->server->connection_name)
            ->select([
                'Name',
                'AccountID',
                $scoreField,
            ])
            ->where($scoreField, '>', 0)
            ->orderByDesc($scoreField)
            ->limit($maxPosition)
            ->get();
    }

    private function getRewardsForRank(int $position): array
    {
        return $this->config->rewards()
            ->where('position_from', '<=', $position)
            ->where('position_to', '>=', $position)
            ->first()?->rewards ?? [];
    }

    private function processRanking(Character $character, int $rank, array $rewards): void
    {
        if ($character->{$this->type->weeklyScoreField()}) {
            $this->archiveRanking($character, $rank, $rewards);
            $this->distributeRewards($character, $rank, $rewards);
        }
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

    private function distributeRewards(Character $character, int $rank, array $rewards): void
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
            $this->sendRewardNotification($user, $character, $rank, $resourceType, $reward['amount']);
        }
    }

    private function logRewardDistribution($user, $character, $rank, $resourceType, $amount): void
    {
        activity('weekly_ranking_reward')
            ->performedOn($user)
            ->withProperties([
                'activity_type' => ActivityType::INCREMENT->value,
                'amount' => $this->format($amount),
                'character' => $character->Name,
                'rank' => $rank,
                'score' => $character->{$this->type->weeklyScoreField()},
                'reward_type' => $resourceType->value,
                'ranking_type' => $this->type->label(),
                'server' => $this->config->server->name,
            ])
            ->log("Received weekly {$this->type->label()} ranking reward for rank #{$rank} ({$resourceType->value}).");
    }

    private function sendRewardNotification($user, $character, $rank, $resourceType, $amount): void
    {
        $title = match ($resourceType) {
            ResourceType::TOKENS => 'Ranking Reward: Tokens',
            ResourceType::CREDITS => 'Ranking Reward: Credits',
            ResourceType::ZEN => 'Ranking Reward: Zen',
            default => 'Ranking Reward',
        };

        if ($this->type === RankingScoreType::EVENTS) {
            SendNotification::make($title)
                ->body('Your character :character earned rank #:rank in the weekly events ranking and received :amount :resource.', [
                    'character' => $character->Name,
                    'rank' => $rank,
                    'amount' => $this->format($amount),
                    'resource' => $resourceType->value,
                ])
                ->send($user);
        } else {
            SendNotification::make($title)
                ->body('Your character :character earned rank #:rank in the weekly hunting ranking and received :amount :resource.', [
                    'character' => $character->Name,
                    'rank' => $rank,
                    'amount' => $this->format($amount),
                    'resource' => $resourceType->value,
                ])
                ->send($user);
        }
    }

    private function format(int $amount): string
    {
        return number_format($amount);
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
