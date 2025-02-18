<?php

namespace App\Console\Commands;

use App\Models\Game\Entry;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CleanEventEntriesCommand extends Command
{
    protected $signature = 'game:clean-event-entries';

    protected $description = 'Clean all event entries at midnight';

    public function handle(): void
    {
        try {
            $count = Entry::count();
            Entry::query()->delete();

            activity('event_entries')
                ->withProperties([
                    'entries_cleaned' => $count,
                    'timestamp' => now()->toDateTimeString(),
                ])
                ->log('Daily event entries cleanup completed.');

            $this->info('Event entries cleaned successfully.');
        } catch (Exception $e) {
            Log::error('Event Entries Cleanup Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'timestamp' => now()->toDateTimeString(),
            ]);

            $this->error('Failed to clean event entries.');

            throw $e;
        }
    }
}
