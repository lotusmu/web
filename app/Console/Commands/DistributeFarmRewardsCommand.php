<?php

namespace App\Console\Commands;

use App\Actions\Partner\DistributeFarmRewards;
use Illuminate\Console\Command;

class DistributeFarmRewardsCommand extends Command
{
    protected $signature = 'partners:distribute-farm';

    protected $description = 'Distribute weekly farm rewards to all active partners';

    public function handle(DistributeFarmRewards $action): int
    {
        $this->info('Starting weekly farm distribution...');

        $results = $action->handle();

        $this->info('Farm Distribution Results:');
        $this->table(
            ['Metric', 'Count'],
            [
                ['Partners Processed', $results['processed']],
                ['Farm Distributed', $results['distributed']],
                ['Errors', $results['errors']],
            ]
        );

        if ($results['errors'] > 0) {
            $this->error("⚠️  {$results['errors']} errors occurred. Check the logs for details.");

            return Command::FAILURE;
        }

        $this->info("✅ Successfully distributed farm rewards to {$results['distributed']} active partners.");

        return Command::SUCCESS;
    }
}
