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
        'discord_username',
        'streaming_hours_per_day',
        'streaming_days_per_week',
        'videos_per_week',
        'content_creation_months',
        'average_live_viewers',
        'average_video_views',
        'status',
        'reviewed_at',
        'notes',
    ];

    protected $casts = [
        'platforms' => 'array',
        'channels' => 'array',
        'reviewed_at' => 'datetime',
        'status' => ApplicationStatus::class,
        'streaming_hours_per_day' => 'integer',
        'streaming_days_per_week' => 'integer',
        'videos_per_week' => 'integer',
        'content_creation_months' => 'integer',
        'average_live_viewers' => 'integer',
        'average_video_views' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function partner(): HasOne
    {
        return $this->hasOne(Partner::class);
    }

    public function isStreamingContent(): bool
    {
        return in_array($this->content_type, ['streaming', 'both']);
    }

    public function isVideoContent(): bool
    {
        return in_array($this->content_type, ['content', 'both']);
    }
}
