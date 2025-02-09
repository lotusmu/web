<?php

namespace App\Actions\Rankings;

use App\Enums\Utility\RankingLogStatus;
use App\Enums\Utility\RankingScoreType;
use App\Models\Game\Ranking\WeeklyRankingConfiguration;
use Exception;
use Illuminate\Support\Facades\DB;

class RecoverWeeklyRankings
{
    public function handle(WeeklyRankingConfiguration $config): void
    {
        try {
            $this->startRecovery($config);
            $this->recoverRankingTypes($config);
            $this->completeRecovery($config);
        } catch (Exception $e) {
            $this->handleError($e, $config);
        }
    }

    private function startRecovery(WeeklyRankingConfiguration $config): void
    {
        DB::beginTransaction();

        activity('weekly_rankings')
            ->event('recovery')
            ->withProperties([
                'server' => $config->server->name,
                'status' => RankingLogStatus::STARTED,
                'last_attempt' => $config->last_processing_start,
            ])
            ->log('Starting rankings recovery process');
    }

    private function recoverRankingTypes(WeeklyRankingConfiguration $config): void
    {
        $completedTypes = $config->processing_state ?? [];

        foreach (RankingScoreType::cases() as $type) {
            if ($this->isTypeProcessed($completedTypes, $type)) {
                continue;
            }

            $this->processType($config, $type);
            $this->updateProcessingState($config, $type);
        }
    }

    private function isTypeProcessed(array $completedTypes, RankingScoreType $type): bool
    {
        return isset($completedTypes[$type->value]);
    }

    private function processType(WeeklyRankingConfiguration $config, RankingScoreType $type): void
    {
        (new ProcessRankingType(
            config: $config,
            type: $type,
            cycleStart: $config->getNextResetDate()->subWeek(),
            cycleEnd: $config->getNextResetDate()
        ))->handle();
    }

    private function updateProcessingState(WeeklyRankingConfiguration $config, RankingScoreType $type): void
    {
        $config->processing_state = array_merge(
            $config->processing_state ?? [],
            [$type->value => now()->format('Y-m-d H:i:s')]
        );
        $config->save();
    }

    private function completeRecovery(WeeklyRankingConfiguration $config): void
    {
        $config->update([
            'last_successful_processing' => now(),
            'last_processing_start' => null,
            'processing_state' => null,
        ]);

        activity('weekly_rankings')
            ->event('recovery')
            ->withProperties([
                'server' => $config->server->name,
                'status' => RankingLogStatus::SUCCESS,
            ])
            ->log('Rankings recovery completed successfully');

        DB::commit();
    }

    private function handleError(Exception $e, WeeklyRankingConfiguration $config): void
    {
        DB::rollBack();

        activity('weekly_rankings')
            ->event('recovery')
            ->withProperties([
                'server' => $config->server->name,
                'status' => RankingLogStatus::FAILED,
                'error' => $e->getMessage(),
            ])
            ->log('Rankings recovery failed');

        throw $e;
    }
}
