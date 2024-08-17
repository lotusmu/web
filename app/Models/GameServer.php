<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameServer extends Model
{
    protected $fillable = [
        'name',
        'connection_name',
        'experience_rate',
        'drop_rate',
        'is_active',
    ];
}
