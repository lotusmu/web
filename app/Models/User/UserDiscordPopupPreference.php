<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserDiscordPopupPreference extends Model
{
    protected $fillable = [
        'user_id',
        'joined_discord',
        'never_show_again',
        'last_shown_at',
        'last_declined_at',
    ];

    protected $casts = [
        'joined_discord' => 'boolean',
        'never_show_again' => 'boolean',
        'last_shown_at' => 'datetime',
        'last_declined_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
