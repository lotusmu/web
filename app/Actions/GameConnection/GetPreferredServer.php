<?php

namespace App\Actions\GameConnection;

use App\Models\Utility\GameServer;
use Illuminate\Support\Facades\Session;

class GetPreferredServer
{
    public function execute(): array
    {
        $serverId = $this->getPreferredServerId();
        $server = $this->getServerById($serverId);

        Session::put([
            'selected_server_id' => $server->id,
            'game_db_connection' => $server->connection_name,
        ]);

        return [
            'server_id' => $server->id,
            'connection_name' => $server->connection_name,
            'server' => $server,
        ];
    }

    private function getPreferredServerId(): int
    {
        // Priority 1: Current session
        if ($sessionServerId = Session::get('selected_server_id')) {
            if ($this->isValidServer($sessionServerId)) {
                return $sessionServerId;
            }
        }

        // Priority 2: Cookie preference
        if ($cookieServerId = request()->cookie('preferred_server_id')) {
            if ($this->isValidServer((int) $cookieServerId)) {
                return (int) $cookieServerId;
            }
        }

        // Priority 3: Default server
        return $this->getDefaultServerId();
    }

    private function isValidServer(int $serverId): bool
    {
        return GameServer::where('id', $serverId)
            ->where('is_active', true)
            ->exists();
    }

    private function getServerById(int $serverId): GameServer
    {
        return GameServer::where('id', $serverId)
            ->where('is_active', true)
            ->firstOr(function () {
                return $this->getDefaultServer();
            });
    }

    private function getDefaultServerId(): int
    {
        return GameServer::where('is_active', true)
            ->orderBy('id')
            ->value('id') ?? 1;
    }

    private function getDefaultServer(): GameServer
    {
        $server = GameServer::where('is_active', true)
            ->orderBy('id')
            ->first();

        if ($server) {
            return $server;
        }

        $fallback = new GameServer;
        $fallback->id = 1;
        $fallback->connection_name = 'gamedb_main';

        return $fallback;
    }
}
