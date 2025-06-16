<?php

namespace App\Console\Commands;

use App\Actions\Stream\GenerateStreamAnalytics;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;

class GenerateStreamAnalyticsCommand extends Command
{
    protected $signature = 'stream:analytics
                           {--date= : Specific date to generate analytics for (YYYY-MM-DD)}
                           {--week : Generate analytics for the current week}
                           {--yesterday : Generate analytics for yesterday}';

    protected $description = 'Generate stream analytics for partners';

    public function handle(GenerateStreamAnalytics $action): int
    {
        $this->info('🎮 Generating stream analytics...');

        try {
            if ($this->option('week')) {
                $results = $action->generateWeeklyAnalytics();
                $this->info("✅ Weekly analytics generated for week starting: {$results['week']}");
                $this->info("📊 Days processed: {$results['days_processed']}");
            } else {
                $date = $this->getTargetDate();
                $results = $action->handle($date);

                $this->info("✅ Analytics generated for: {$results['date']}");
                $this->table(
                    ['Metric', 'Count'],
                    [
                        ['Partners Processed', $results['partners_processed']],
                        ['Analytics Created', $results['analytics_created']],
                        ['Analytics Updated', $results['analytics_updated']],
                    ]
                );
            }

            return Command::SUCCESS;

        } catch (Exception $e) {
            $this->error("❌ Error generating analytics: {$e->getMessage()}");

            return Command::FAILURE;
        }
    }

    private function getTargetDate(): Carbon
    {
        if ($this->option('date')) {
            return Carbon::parse($this->option('date'));
        }

        if ($this->option('yesterday')) {
            return today()->subDay();
        }

        return today();
    }
}
