<?php

namespace App\Console\Commands;

use App\Actions\Rankings\RecoverWeeklyRankings;
use App\Models\Game\Ranking\WeeklyRankingConfiguration;
use Exception;
use Illuminate\Console\Command;

class RecoverWeeklyRankingsCommand extends Command
{
    protected $signature = 'rankings:recover-weekly {server}';

    protected $description = 'Recover failed weekly rankings process for specific server';

    public function handle(): void
    {
        $config = WeeklyRankingConfiguration::query()
            ->whereHas('server', fn ($q) => $q->where('name', $this->argument('server')))
            ->where('is_enabled', true)
            ->first();

        if (! $config) {
            $this->error('Server configuration not found');

            return;
        }

        if (! $config->last_processing_start) {
            $this->error('No failed processing found');

            return;
        }

        $this->info('Starting recovery process...');

        try {
            $recovery = new RecoverWeeklyRankings;
            $recovery->handle($config);

            $this->info('Recovery completed successfully');
        } catch (Exception $e) {
            $this->error('Recovery failed: '.$e->getMessage());
        }
    }
}
