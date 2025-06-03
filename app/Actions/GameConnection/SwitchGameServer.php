<?php

namespace App\Actions\GameConnection;

use App\Models\Utility\GameServer;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class SwitchGameServer
{
    public function execute(int $serverId): GameServer
    {
        $server = GameServer::findOrFail($serverId);

        Session::put([
            'selected_server_id' => $serverId,
            'game_db_connection' => $server->connection_name,
        ]);

        Cookie::queue('preferred_server_id', $serverId, 60 * 24 * 30); // 30 days

        return $server;
    }
}
