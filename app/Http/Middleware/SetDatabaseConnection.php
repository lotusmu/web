<?php

namespace App\Http\Middleware;

use App\Services\DatabaseConnectionService;
use Closure;

class SetDatabaseConnection
{
    public function handle($request, Closure $next)
    {
        $connectionName = session('selected_server_connection');

        if ($connectionName) {
            DatabaseConnectionService::setConnection($connectionName);
        }

        return $next($request);
    }
}
