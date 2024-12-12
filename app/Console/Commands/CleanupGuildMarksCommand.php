<?php

namespace App\Console\Commands;

use App\Models\Game\Guild;
use Illuminate\Console\Command;

class CleanupGuildMarksCommand extends Command
{
    protected $signature = 'guild:cleanup-marks {days=30}';

    protected $description = 'Clean up old guild mark images';

    public function handle(): void
    {
        Guild::cleanupOldMarkImages($this->argument('days'));

        $this->info('Guild marks cleaned up successfully.');
    }
}
