<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TestCommand extends Command
{
    protected $signature = 'test:scheduler';

    protected $description = 'Test if scheduler is running';

    public function handle()
    {
        Log::info('Scheduler test run at '.now());
        $this->info('Scheduler test executed');

        return 0;
    }
}
