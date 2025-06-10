<?php

namespace App\Models\Partner;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PromoCodeUsage extends Model
{
    protected $fillable = [
        'partner_id',
        'user_id',
        'promo_code',
        'donation_amount',
        'tokens_earned',
        'tokens_discount',
        'transaction_id',
        'paid_at',
    ];

    protected $casts = [
        'donation_amount' => 'decimal:2',
        'tokens_earned' => 'integer',
        'tokens_discount' => 'integer',
        'paid_at' => 'datetime',
    ];

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
