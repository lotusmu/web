<?php

namespace App\Models\Payment;

use App\Enums\OrderStatus;
use App\Enums\PaymentProvider;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    private const ALLOWED_TRANSITIONS = [
        OrderStatus::PENDING->value => [
            OrderStatus::COMPLETED->value,
            OrderStatus::FAILED->value,
            OrderStatus::CANCELLED->value,
            OrderStatus::EXPIRED->value,
        ],
        OrderStatus::COMPLETED->value => [
            OrderStatus::REFUNDED->value,
        ],
        OrderStatus::FAILED->value => [
            OrderStatus::COMPLETED->value,
        ],
    ];

    public function canTransitionTo(OrderStatus $newStatus): bool
    {
        return in_array(
            $newStatus->value,
            self::ALLOWED_TRANSITIONS[$this->status->value] ?? []
        );
    }

    public function isValidForProcessing(): bool
    {
        return $this->status === OrderStatus::PENDING
            && ! $this->expires_at?->isPast();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(TokenPackage::class, 'token_package_id');
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class)->orderBy('created_at', 'desc');
    }
}
