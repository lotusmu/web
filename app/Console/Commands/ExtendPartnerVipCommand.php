<?php

namespace App\Console\Commands;

use App\Actions\Partner\ExtendPartnerVip;
use Illuminate\Console\Command;

class ExtendPartnerVipCommand extends Command
{
    protected $signature = 'partners:extend-vip';

    protected $description = 'Extend VIP status for all active partners by 1 day';

    public function handle(ExtendPartnerVip $action): int
    {
        $this->info('Starting partner VIP extension process...');

        $results = $action->handle();

        $this->info('Partner VIP Extension Results:');
        $this->table(
            ['Metric', 'Count'],
            [
                ['Partners Processed', $results['processed']],
                ['VIP Extended', $results['extended']],
                ['VIP Granted (New)', $results['upgraded']],
                ['Errors', $results['errors']],
            ]
        );

        if ($results['errors'] > 0) {
            $this->error("⚠️  {$results['errors']} errors occurred. Check the logs for details.");

            return Command::FAILURE;
        }

        $this->info("✅ Successfully processed {$results['processed']} active partners.");

        return Command::SUCCESS;
    }
}
