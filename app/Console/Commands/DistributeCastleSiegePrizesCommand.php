<?php

namespace App\Console\Commands;

use App\Actions\Castle\DistributePrize;
use App\Models\Game\CastleData;
use App\Models\Utility\CastlePrize;
use App\Models\Utility\GameServer;
use Exception;
use Illuminate\Console\Command;
use Log;

class DistributeCastleSiegePrizesCommand extends Command
{
    protected $signature = 'castle:distribute-prizes';

    protected $description = 'Distribute prizes to Castle Siege winning guild members';

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

            $prizeSetting = CastlePrize::where('game_server_id', $server->id)
                ->where('is_active', true)
                ->first();

            if (! $prizeSetting) {
                $this->warn("No active prize pool found for server: {$server->name}");

                continue;
            }

            if (! $prizeSetting->isWithinActivePeriod()) {
                $this->info("Prize pool not in active period for server: {$server->name}");

                continue;
            }

            $castle = CastleData::first();

            if (! $castle) {
                $this->error("Castle data not found for server: {$server->name}");

                continue;
            }

            if (! $castle->OWNER_GUILD) {
                $this->warn("No winning guild found for server: {$server->name}");

                continue;
            }

            try {
                $distribution = (new DistributePrize(
                    castle: $castle,
                    prizeSetting: $prizeSetting,
                    amount: $prizeSetting->weekly_amount
                ))->handle();

                if ($distribution) {
                    $prizeSetting->remaining_prize_pool -= $prizeSetting->weekly_amount;
                    $prizeSetting->save();

                    $this->info("Successfully distributed {$prizeSetting->weekly_amount} credits to guild {$castle->OWNER_GUILD} on {$server->name}");
                } else {
                    $this->warn("No members eligible for distribution in guild {$castle->OWNER_GUILD} on {$server->name}");
                }
            } catch (Exception $e) {
                $this->error("Error during distribution for server {$server->name}: {$e->getMessage()}");
                Log::error('Castle Siege Prize Distribution Error', [
                    'server' => $server->name,
                    'guild' => $castle->OWNER_GUILD,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }
        }

        return self::SUCCESS;
    }
}
