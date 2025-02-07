<?php

namespace App\Console\Commands;

use App\Actions\Rankings\ProcessWeeklyRankings;
use Illuminate\Console\Command;

class ProcessWeeklyRankingsCommand extends Command
{
    protected $signature = 'rankings:process-weekly';

    protected $description = 'Process weekly rankings, archive results and reset scores';

    public function handle(): void
    {
        $this->info('Processing weekly rankings...');

        $processor = new ProcessWeeklyRankings;
        $processor->handle();

        $this->info('Weekly rankings processed successfully.');
    }
}
