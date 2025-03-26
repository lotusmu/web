<?php

namespace App\Services;

use App\Models\Game\Status;
use App\Models\Utility\GameServer;
use Exception;
use Illuminate\Support\Facades\Cache;
use Log;

class GameServerStatusService
{
    public function updateAllServerStatuses(): void
    {
        GameServer::where('is_active', true)
            ->get()
            ->each(function ($server, $index) {
                if ($index > 0) {
                    usleep(200000); // 200ms delay
                }

                $this->updateServerStatus($server);
            });
    }

    public function updateServerStatus(GameServer $server): array
    {
        $isOnline = $this->checkServerOnline($server);
        $onlineCount = $this->getServerOnlineCount($server);

        $status = [
            'is_online' => $isOnline,
            'online_count' => $onlineCount,
            'multiplied_count' => ceil($onlineCount * $server->online_multiplier),
            'last_updated' => now(),
        ];

        Cache::put("server_status_{$server->id}", $status, now()->addMinutes(config('gameserver.cache.status_ttl')));

        return $status;
    }

    private function checkServerOnline(GameServer $server): bool
    {
        try {
            $socket = fsockopen(
                config("database.connections.{$server->connection_name}.host"),
                config('gameserver.port'),
                $errno,
                $errstr,
                config('gameserver.socket_timeout')
            );

            if ($socket) {
                fclose($socket);

                return true;
            }

            return false;
        } catch (Exception $e) {
            Log::error("Socket connection failed for {$server->name}: {$e->getMessage()}");

            return false;
        }
    }

    private function getServerOnlineCount(GameServer $server): int
    {
        try {
            return Status::on($server->connection_name)
                ->where('ConnectStat', 1)
                ->count();
        } catch (Exception $e) {
            Log::error("Query failed for {$server->name}: {$e->getMessage()}");

            return 0;
        }
    }
}
