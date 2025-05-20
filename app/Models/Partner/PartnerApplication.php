<?php

namespace App\Models\Partner;

use App\Enums\Partner\ApplicationStatus;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PartnerApplication extends Model
{
    protected $fillable = [
        'user_id',
        'content_type',
        'platforms',
        'channels',
        'about_you',
        'status',
        'reviewed_at',
        'notes',
    ];

    protected $casts = [
        'platforms' => 'array',
        'channels' => 'array',
        'reviewed_at' => 'datetime',
        'status' => ApplicationStatus::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function partner(): HasOne
    {
        return $this->hasOne(Partner::class);
    }
}
