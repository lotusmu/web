<?php

namespace App\Actions\Rankings;

use App\Enums\Utility\RankingScoreType;
use App\Models\Game\Ranking\WeeklyRankingConfiguration;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessWeeklyRankings
{
    public function handle(): void
    {
        $configs = WeeklyRankingConfiguration::query()
            ->where('is_enabled', true)
            ->with(['rewards', 'server'])
            ->get();

        Log::info('Found configs:', ['count' => $configs->count()]);

        foreach ($configs as $config) {
            Log::info('Checking config', [
                'server' => $config->server->name,
                'reset_day' => $config->reset_day_of_week,
                'reset_time' => $config->reset_time,
                'should_reset' => $config->shouldProcessReset(),
                'next_reset' => $config->getNextResetDate()->format('Y-m-d H:i:s'),
            ]);

            if (! $config->shouldProcessReset()) {
                Log::info('Skipping config - not time to reset yet');

                continue;
            }

            try {
                DB::beginTransaction();

                foreach (RankingScoreType::cases() as $type) {
                    (new ProcessRankingType(
                        config: $config,
                        type: $type,
                        cycleStart: $config->getNextResetDate()->subWeek(),
                        cycleEnd: $config->getNextResetDate()
                    ))->handle();
                }

                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
                Log::error("Failed to process weekly rankings for server {$config->server->name}", [
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}
