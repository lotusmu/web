<?php

namespace App\Models\Partner;

use App\Enums\Partner\PartnerLevel;
use App\Enums\Partner\PartnerStatus;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Partner extends Model
{
    protected $fillable = [
        'user_id',
        'level',
        'promo_code',
        'commission_rate',
        'vip_until',
        'status',
        'platforms',
        'channels',
        'approved_at',
    ];

    protected $casts = [
        'level' => PartnerLevel::class,
        'status' => PartnerStatus::class,
        'platforms' => 'array',
        'channels' => 'array',
        'approved_at' => 'datetime',
        'vip_until' => 'datetime',
        'commission_rate' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function application(): HasOne
    {
        return $this->hasOne(PartnerApplication::class);
    }

    public function promoCodeUsages(): HasMany
    {
        return $this->hasMany(PromoCodeUsage::class);
    }

    public function rewards(): HasMany
    {
        return $this->hasMany(PartnerReward::class);
    }
}
