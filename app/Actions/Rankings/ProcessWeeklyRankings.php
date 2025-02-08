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
            ->each(fn ($config) => $this->processConfig($config));
    }

    private function getEnabledConfigs(): Collection
    {
        return WeeklyRankingConfiguration::query()
            ->where('is_enabled', true)
            ->with(['rewards', 'server'])
            ->get();
    }

    private function processConfig(WeeklyRankingConfiguration $config): void
    {
        try {
            DB::beginTransaction();

            Log::info('Processing weekly rankings for server', [
                'server' => $config->server->name,
                'cycle_end' => $config->getNextResetDate()->format('Y-m-d H:i:s'),
            ]);

            $this->processRankingTypes($config);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to process weekly rankings', [
                'server' => $config->server->name,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function processRankingTypes(WeeklyRankingConfiguration $config): void
    {
        foreach (RankingScoreType::cases() as $type) {
            (new ProcessRankingType(
                config: $config,
                type: $type,
                cycleStart: $config->getNextResetDate()->subWeek(),
                cycleEnd: $config->getNextResetDate()
            ))->handle();
        }
    }
}
