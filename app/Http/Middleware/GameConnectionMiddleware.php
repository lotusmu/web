<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class GameConnectionMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $gameConnection = session('game_db_connection', 'gamedb_main');

        if (! Config::has("database.connections.{$gameConnection}")) {
            $gameConnection = 'gamedb_main';
        }

        session(['game_db_connection' => $gameConnection]);

        return $next($request);
    }
}
