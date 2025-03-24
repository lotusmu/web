<?php

namespace App\Models\User;

use App\Enums\Survey\MMOTopSite;
use App\Enums\Survey\MUOnlineForum;
use App\Enums\Survey\ReferralSource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReferralSurvey extends Model
{
    protected $fillable = [
        'user_id',
        'referral_source',
        'mmo_top_site',
        'mu_online_forum',
        'custom_source',
        'completed',
        'dismissed',
        'shown_at',
    ];

    protected $casts = [
        'completed' => 'boolean',
        'dismissed' => 'boolean',
        'shown_at' => 'datetime',
        'mmo_top_site' => MMOTopSite::class,
        'mu_online_forum' => MUOnlineForum::class,
        'referral_source' => ReferralSource::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
