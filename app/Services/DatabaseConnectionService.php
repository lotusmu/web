<?php

namespace App\Services;

use App\Models\Utility\GameServer;

class DatabaseConnectionService
{
    public static function setConnection(string $connectionName): GameServer
    {
        $server = GameServer::where('connection_name', $connectionName)->firstOrFail();

        session(['game_db_connection' => $connectionName]);

        return $server;
    }
}
