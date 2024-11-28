<?php

namespace App\Models\Payment;

use App\Enums\OrderStatus;
use App\Enums\PaymentProvider;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'token_package_id',
        'payment_provider',
        'payment_id',
        'amount',
        'currency',
        'status',
        'payment_data',
        'expires_at',
    ];

    protected $casts = [
        'payment_data' => 'array',
        'expires_at' => 'datetime',
        'status' => OrderStatus::class,
        'payment_provider' => PaymentProvider::class,
    ];

    // Relations
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(TokenPackage::class, 'token_package_id');
    }
}
