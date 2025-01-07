<?php

namespace App\Models\Concerns;

trait GameConnection
{
    public function getConnectionName()
    {
        if ($this->connection) {
            return $this->connection;
        }

        return session('game_db_connection', 'gamedb_main');
    }
}
