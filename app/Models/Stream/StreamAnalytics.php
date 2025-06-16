<?php

namespace App\Models\Stream;

use App\Enums\Stream\StreamProvider;
use App\Models\Partner\Partner;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StreamAnalytics extends Model
{
    protected $fillable = [
        'partner_id',
        'provider',
        'date',
        'total_hours_streamed',
        'total_viewers',
        'stream_count',
        'average_stream_duration',
        'scheduled_vs_actual_hours',
        'days_streamed_this_week',
        'longest_streak_days',
        'viewer_growth_rate',
        'chat_activity_score',
    ];

    protected $casts = [
        'provider' => StreamProvider::class,
        'date' => 'date',
        'total_hours_streamed' => 'decimal:2',
        'total_viewers' => 'integer',
        'stream_count' => 'integer',
        'average_stream_duration' => 'decimal:2',
        'scheduled_vs_actual_hours' => 'decimal:2',
        'days_streamed_this_week' => 'integer',
        'longest_streak_days' => 'integer',
        'viewer_growth_rate' => 'decimal:2',
        'chat_activity_score' => 'decimal:2',
    ];

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    public function getConsistencyScore(): string
    {
        if ($this->scheduled_vs_actual_hours >= 0.9) {
            return 'Excellent';
        } elseif ($this->scheduled_vs_actual_hours >= 0.7) {
            return 'Good';
        } elseif ($this->scheduled_vs_actual_hours >= 0.5) {
            return 'Fair';
        }

        return 'Poor';
    }

    public function getGrowthTrend(): string
    {
        if ($this->viewer_growth_rate > 10) {
            return 'High Growth';
        } elseif ($this->viewer_growth_rate > 0) {
            return 'Growing';
        } elseif ($this->viewer_growth_rate > -10) {
            return 'Stable';
        }

        return 'Declining';
    }

    public function scopeForProvider($query, StreamProvider $provider)
    {
        return $query->where('provider', $provider);
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('date', now()->month)->whereYear('date', now()->year);
    }
}
