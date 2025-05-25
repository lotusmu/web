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
        'commission_amount',
        'discount_amount',
        'transaction_id',
        'paid_at',
    ];

    protected $casts = [
        'donation_amount' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
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
