<?php

namespace App\Models\Concerns;

trait GameConnection
{
    public function getConnectionName()
    {
        return session('game_db_connection', 'gamedb_main');
    }
}
