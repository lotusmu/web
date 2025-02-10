<?php

namespace App\Models\Game;

use App\Models\Concerns\GameConnection;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Status extends Model
{
    use GameConnection;

    protected $table = 'MEMB_STAT';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'memb___id',
        'ConnectStat',
        'ConnectTM',
        'DisConnectTM',
        'OnlineHours',
    ];

    protected $casts = [
        'memb___id' => 'string',
        'ConnectStat' => 'boolean',
        'ConnectTM' => 'datetime',
        'DisConnectTM' => 'datetime',
        'OnlineHours' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'memb___id', 'name');
    }

    public function getCurrentStatusAttribute(): string
    {
        return $this->ConnectStat ? __('Online') : __('Offline');
    }

    public function getLastLoginAttribute(): string
    {
        return $this->ConnectTM?->diffForHumans() ?? __('Never');
    }

    public function getLastDisconnectAttribute(): string
    {
        return $this->DisConnectTM?->diffForHumans() ?? __('Never');
    }
}
