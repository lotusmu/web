<?php

namespace App\Actions;

use App\Models\Utility\GameServer;
use Illuminate\Support\Facades\Session;

class SwitchGameServer
{
    public function execute(int $serverId)
    {
        $server = GameServer::findOrFail($serverId);

        Session::put([
            'selected_server_id' => $serverId,
            'game_db_connection' => $server->connection_name,
        ]);

        return $server;
    }
}
