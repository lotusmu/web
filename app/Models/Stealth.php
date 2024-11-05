<?php

namespace App\Models;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stealth extends Model
{
    protected $fillable = [
        'user_id',
        'expires_at',
        'duration',
        'cost',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'duration' => 'integer',
        'cost' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isActive(): bool
    {
        return $this->expires_at->isFuture();
    }

    public static function getDefaultDuration(): int
    {
        return self::firstOrCreate([], [
            'duration' => 7,
            'cost' => 60,
        ])->duration;
    }

    public static function getDefaultCost(): int
    {
        return self::firstOrCreate([], [
            'duration' => 7,
            'cost' => 60,
        ])->cost;
    }
}
