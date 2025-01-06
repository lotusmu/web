<?php

namespace App\Models\Utility;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameServer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'connection_name',
        'experience_rate',
        'drop_rate',
        'is_active',
        'server_version',
        'max_resets',
        'starting_resets',
        'reset_zen',
        'clear_pk_zen',
    ];

    public function getServerName(): string
    {
        return "{$this->name} - x{$this->experience_rate}";
    }
}
