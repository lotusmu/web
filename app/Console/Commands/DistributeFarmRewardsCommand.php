<?php

namespace App\Console\Commands;

use App\Actions\Partner\DistributeFarmRewards;
use App\Models\Utility\GameServer;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DistributeFarmRewardsCommand extends Command
{
    protected $signature = 'partners:distribute-farm';

    protected $description = 'Distribute weekly farm rewards to all active partners across all active servers';

    public function handle(DistributeFarmRewards $action): int
    {
        $activeServers = GameServer::where('is_active', true)->get();

        if ($activeServers->isEmpty()) {
            $this->warn('No active game servers found.');

            return Command::SUCCESS;
        }

        $totalResults = [
            'processed' => 0,
            'distributed' => 0,
            'errors' => 0,
        ];

        foreach ($activeServers as $server) {
            $this->info("Processing server: {$server->name}");
            session(['game_db_connection' => $server->connection_name]);

            try {
                $results = $action->handle();

                // Accumulate results
                $totalResults['processed'] += $results['processed'];
                $totalResults['distributed'] += $results['distributed'];
                $totalResults['errors'] += $results['errors'];

                $this->info("Server {$server->name}: {$results['distributed']} partners rewarded, {$results['errors']} errors");

            } catch (Exception $e) {
                $totalResults['errors']++;
                Log::error('Farm distribution failed for server', [
                    'server' => $server->name,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);

                $this->error("Failed to distribute farm rewards for server: {$server->name}");
            }
        }

        $this->info('Overall Farm Distribution Results:');
        $this->table(
            ['Metric', 'Count'],
            [
                ['Partners Processed', $totalResults['processed']],
                ['Farm Distributed', $totalResults['distributed']],
                ['Errors', $totalResults['errors']],
            ]
        );

        if ($totalResults['errors'] > 0) {
            $this->error("⚠️  {$totalResults['errors']} errors occurred. Check the logs for details.");

            return Command::FAILURE;
        }

        $this->info("✅ Successfully distributed farm rewards to {$totalResults['distributed']} active partners across all servers.");

        return Command::SUCCESS;
    }
}
