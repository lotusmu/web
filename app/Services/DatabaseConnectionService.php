<?php

namespace App\Services;

use App\Models\GameServer;
use Illuminate\Support\Facades\Config;

class DatabaseConnectionService
{
    public static function setConnection(string $connectionName)
    {
        $server = GameServer::where('connection_name', $connectionName)->firstOrFail();

        Config::set('database.default', $connectionName);

        app('db')->purge($connectionName);

        return $server;
    }
}
