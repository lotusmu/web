<?php

namespace App\Models\Stream;

use App\Enums\Stream\StreamProvider;
use App\Models\Partner\Partner;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StreamSession extends Model
{
    protected $fillable = [
        'partner_id',
        'provider',
        'external_stream_id',
        'channel_name',
        'title',
        'game_category',
        'language',
        'stream_tags',
        'mature_content',
        'started_at',
        'ended_at',
        'peak_viewers',
        'average_viewers',
        'day_of_week',
        'hour_of_day',
        'stream_quality',
    ];

    protected $casts = [
        'provider' => StreamProvider::class,
        'stream_tags' => 'array',
        'mature_content' => 'boolean',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'peak_viewers' => 'integer',
        'average_viewers' => 'integer',
        'day_of_week' => 'integer',
        'hour_of_day' => 'integer',
    ];

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    public function getDurationInHours(): float
    {
        if (! $this->ended_at) {
            // Stream is still live - calculate from start to now
            return $this->started_at->diffInSeconds(now()) / 3600;
        }

        // Stream ended - calculate from start to end
        return $this->started_at->diffInSeconds($this->ended_at) / 3600;
    }

    public function isLive(): bool
    {
        return $this->ended_at === null;
    }

    public function getEmbedUrl(): string
    {
        return $this->provider->getEmbedUrl($this->channel_name);
    }

    public function scopeLive($query)
    {
        return $query->whereNull('ended_at');
    }

    public function scopeForProvider($query, StreamProvider $provider)
    {
        return $query->where('provider', $provider);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('started_at', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('started_at', [now()->startOfWeek(), now()->endOfWeek()]);
    }
}
