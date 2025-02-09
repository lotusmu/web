<?php

namespace App\Actions\Rankings;

use App\Enums\Utility\RankingScoreType;
use App\Models\Game\Ranking\WeeklyRankingConfiguration;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessWeeklyRankings
{
    public function handle(): void
    {
        $this->getEnabledConfigs()
            ->filter(fn ($config) => $config->shouldProcessReset())
            ->filter(fn ($config) => ! $this->isAlreadyProcessing($config))
            ->each(fn ($config) => $this->processConfig($config));
    }

    private function getEnabledConfigs(): Collection
    {
        return WeeklyRankingConfiguration::query()
            ->where('is_enabled', true)
            ->with(['rewards', 'server'])
            ->get();
    }

    private function isAlreadyProcessing(WeeklyRankingConfiguration $config): bool
    {
        if ($config->last_processing_start && $config->last_processing_start->gt(now()->subHours(1))) {
            Log::warning('Skipping - another process might be running', [
                'server' => $config->server->name,
                'started_at' => $config->last_processing_start,
            ]);

            return true;
        }

        return false;
    }

    private function processConfig(WeeklyRankingConfiguration $config): void
    {
        try {
            $this->startProcessing($config);
            $this->processRankingTypes($config);
            $this->completeProcessing($config);
        } catch (Exception $e) {
            DB::rollBack();
            $this->logError($config, $e);
        }
    }

    private function startProcessing(WeeklyRankingConfiguration $config): void
    {
        $config->update([
            'last_processing_start' => now(),
            'processing_state' => [],
        ]);

        DB::beginTransaction();

        Log::info('Processing weekly rankings for server', [
            'server' => $config->server->name,
            'cycle_end' => $config->getNextResetDate()->format('Y-m-d H:i:s'),
        ]);
    }

    private function processRankingTypes(WeeklyRankingConfiguration $config): void
    {
        foreach (RankingScoreType::cases() as $type) {
            $processor = new ProcessRankingType(
                config: $config,
                type: $type,
                cycleStart: $config->getNextResetDate()->subWeek(),
                cycleEnd: $config->getNextResetDate()
            );

            $processor->handle();

            $this->updateProcessingState($config, $type);
        }
    }

    private function updateProcessingState(WeeklyRankingConfiguration $config, RankingScoreType $type): void
    {
        $currentState = (array) $config->processing_state;
        $currentState[$type->value] = now()->format('Y-m-d H:i:s');

        $config->processing_state = $currentState;
        $config->save();
    }

    private function completeProcessing(WeeklyRankingConfiguration $config): void
    {
        $config->update([
            'last_successful_processing' => now(),
            'last_processing_start' => null,
            'processing_state' => null,
        ]);

        DB::commit();
    }

    private function logError(WeeklyRankingConfiguration $config, Exception $e): void
    {
        Log::error('Failed to process weekly rankings', [
            'server' => $config->server->name,
            'error' => $e->getMessage(),
            'completed_types' => $config->processing_state,
        ]);
    }
}
