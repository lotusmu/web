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
        'token_percentage',
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
        'token_percentage' => 'decimal:2',
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

    public function reviews(): HasMany
    {
        return $this->hasMany(PartnerReview::class);
    }

    public function getTotalTokensEarned(): int
    {
        return $this->promoCodeUsages()->sum('partner_tokens');
    }

    public function getTokensEarnedThisMonth(): int
    {
        return $this->promoCodeUsages()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('partner_tokens');
    }

    public function hasBeenReviewedThisWeek(): bool
    {
        return $this->reviews()
            ->forWeek(now()->week, now()->year)
            ->exists();
    }

    public function getThisWeekReview(): ?PartnerReview
    {
        return $this->reviews()
            ->forWeek(now()->week, now()->year)
            ->first();
    }
}
