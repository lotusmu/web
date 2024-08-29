<?php

namespace App\Models\Utility;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

abstract class GameModel extends Model
{
    protected static bool $connectionSet = false;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setGameConnection();
    }

    public function setGameConnection(): void
    {
        $gameConnection = session('game_db_connection', 'gamedb_main');

        if (Config::has("database.connections.{$gameConnection}")) {
            $this->setConnection($gameConnection);
        } else {
            $this->setConnection('gamedb_main');
        }
    }

    public static function boot(): void
    {
        parent::boot();

        static::retrieved(function ($model) {
            $model->setGameConnection();
        });
    }
}
