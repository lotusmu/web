<?php

namespace App\Http\Middleware;

use App\Actions\GameConnection\GetPreferredServer;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class GameConnectionMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $preference = app(GetPreferredServer::class)->execute();

        $gameConnection = $preference['connection_name'];

        // Validate connection exists in config
        if (! Config::has("database.connections.{$gameConnection}")) {
            $gameConnection = 'gamedb_main';
        }

        // Ensure session has the resolved connection
        session(['game_db_connection' => $gameConnection]);

        return $next($request);
    }
}
