<?php

namespace App\Models\Game;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

abstract class GameModel extends Model
{
    protected $connection = 'gamedb_main';

    public function getConnection()
    {
        $selectedConnection = session('selected_server_connection');

        if ($selectedConnection && Config::get("database.connections.{$selectedConnection}")) {
            return app('db')->connection($selectedConnection);
        }

        return app('db')->connection($this->connection);
    }
}
