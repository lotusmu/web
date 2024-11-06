<?php

namespace App\Models\User;

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
}
