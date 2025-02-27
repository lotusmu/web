<?php

namespace App\Console\Commands;

use App\Models\Game\Entry;
use App\Models\Utility\GameServer;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CleanEventEntriesCommand extends Command
{
    protected $signature = 'game:clean-event-entries';

    protected $description = 'Clean all event entries at midnight';

    public function handle(): int
    {
        $activeServers = GameServer::where('is_active', true)->get();

        if ($activeServers->isEmpty()) {
            $this->warn('No active game servers found.');

            return self::SUCCESS;
        }

        foreach ($activeServers as $server) {
            $this->info("Processing server: {$server->name}");
            session(['game_db_connection' => $server->connection_name]);

            try {
                $count = Entry::count();
                Entry::query()->delete();

                activity('event_entries')
                    ->withProperties([
                        'server' => $server->name,
                        'entries_cleaned' => $count,
                        'timestamp' => now()->toDateTimeString(),
                    ])
                    ->log('Daily event entries cleanup completed in :properties.server.');

                $this->info("Event entries cleaned successfully for server: {$server->name} ({$count} entries)");
            } catch (Exception $e) {
                Log::error('Event Entries Cleanup Error', [
                    'server' => $server->name,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'timestamp' => now()->toDateTimeString(),
                ]);

                $this->error("Failed to clean event entries for server: {$server->name}");
            }
        }

        return self::SUCCESS;
    }
}
