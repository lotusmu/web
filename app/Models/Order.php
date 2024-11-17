<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\PaymentProvider;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'token_package_id',
        'payment_provider',
        'payment_id',
        'amount',
        'currency',
        'status',
        'payment_data',
    ];

    protected $casts = [
        'payment_data' => 'array',
        'status' => OrderStatus::class,
        'payment_provider' => PaymentProvider::class,
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(TokenPackage::class, 'token_package_id');
    }
}
