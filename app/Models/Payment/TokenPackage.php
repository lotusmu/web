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
}
