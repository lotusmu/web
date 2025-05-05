<?php

namespace App\Console\Commands;

use App\Actions\Rankings\ProcessHallOfFame;
use App\Models\Utility\GameServer;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ProcessHallOfFameCommand extends Command
{
    protected $signature = 'game:process-hall-of-fame';

    protected $description = 'Process Hall of Fame winners and update HofWins';

    public function handle(ProcessHallOfFame $action): int
    {
        $activeServers = GameServer::where('is_active', true)->get();

        if ($activeServers->isEmpty()) {
            $this->warn('No active game servers found.');

            return self::SUCCESS;
        }

        foreach ($activeServers as $server) {
            $this->info("Processing server: {$server->name}");
            session(['game_db_connection' => $server->connection_name]);

            Cache::forget("hall_of_fame_winners_{$server->connection_name}");

            try {
                $result = $action->handle();

                activity('hall_of_fame')
                    ->withProperties([
                        'server' => $server->name,
                        'characters_updated' => $result['updated'],
                        'failed_characters' => $result['failed'],
                        'timestamp' => now()->toDateTimeString(),
                        'cache_cleared' => true,
                    ])
                    ->log('Hall of Fame processed successfully for :properties.server');

                $this->info("Hall of Fame processed for {$server->name}: {$result['updated']} characters updated");
            } catch (Exception $e) {
                Log::error('Hall of Fame Processing Error', [
                    'server' => $server->name,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'timestamp' => now()->toDateTimeString(),
                ]);

                $this->error("Failed to process Hall of Fame for server: {$server->name}");
            }
        }

        return self::SUCCESS;
    }
}
