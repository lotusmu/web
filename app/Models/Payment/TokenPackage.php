<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TokenPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'stripe_product_id',
        'stripe_price_id',
        'name',
        'tokens_amount',
        'price',
        'is_popular',
    ];

    public function getCachedPrice(): float
    {
        return cache()->remember(
            "package_price_{$this->id}",
            now()->addHour(),
            fn () => $this->price
        );
    }

    protected static function booted(): void
    {
        static::updated(function ($package) {
            cache()->forget("package_price_{$package->id}");
        });
    }
}
