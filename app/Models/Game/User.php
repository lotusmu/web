<?php

namespace App\Models\Game;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Model
{
    protected $connection = 'game_server_1';

    protected $table = 'MEMB_INFO';

    protected $primaryKey = 'memb___id';

    protected $keyType = 'string';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'memb___id',
        'memb__pwd',
        'memb_name',
        'sno__numb',
        'appl_days',
        'mail_chek',
        'bloc_code',
        'ctl1_code',
        'AccountLevel',
        'AccountExpireDate',
    ];

    // Define accessors
    public function getUsernameAttribute()
    {
        return $this->memb___id;
    }

    public function getPasswordAttribute()
    {
        return $this->memb__pwd;
    }

    // Define mutators
    public function setUsernameAttribute($value): void
    {
        $this->attributes['memb___id'] = $value;
    }

    public function setPasswordAttribute($value): void
    {
        $this->attributes['memb__pwd'] = $value;
    }


    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'memb___id', 'username');
    }
}

