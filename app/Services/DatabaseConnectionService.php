<?php

namespace App\Services;

use App\Models\Utility\GameServer;
use Illuminate\Support\Facades\Config;

class DatabaseConnectionService
{
    public static function setConnection(string $connectionName): GameServer
    {
        $server = GameServer::where('connection_name', $connectionName)->firstOrFail();

        Config::set('database.connections.gamedb_main', $connectionName);

        app('db')->purge($connectionName);

        return $server;
    }
}
