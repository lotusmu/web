<?php

namespace App\Console\Commands;

use App\Actions\Stream\SyncStreamsAction;
use Exception;
use Illuminate\Console\Command;

class SyncStreamsCommand extends Command
{
    protected $signature = 'stream:sync
                           {--force : Force sync even if recently completed}';

    protected $description = 'Sync live streams from external APIs (Twitch, etc.)';

    public function handle(SyncStreamsAction $action): int
    {
        $this->info('🔄 Syncing streams from external APIs...');

        try {
            $results = $action->handle();

            if ($results['success']) {
                $this->info('✅ '.$action->getFormattedResults($results));

                return Command::SUCCESS;
            } else {
                $this->error('❌ Sync failed: '.implode(', ', $results['errors']));

                return Command::FAILURE;
            }

        } catch (Exception $e) {
            $this->error("❌ Error during stream sync: {$e->getMessage()}");

            return Command::FAILURE;
        }
    }
}
