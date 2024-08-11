<?php

namespace App\Models\Game;

use Illuminate\Database\Eloquent\Casts\Attribute;
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
        'mail_addr',
        'appl_days',
        'mail_chek',
        'bloc_code',
        'ctl1_code',
        'AccountLevel',
        'AccountExpireDate',
    ];

    protected function username(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->memb___id,
            set: fn ($value) => ['memb___id' => $value]
        );
    }

    protected function password(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->memb__pwd,
            set: fn ($value) => ['memb__pwd' => $value]
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'memb___id', 'username');
    }
}
