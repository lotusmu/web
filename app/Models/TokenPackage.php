<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TokenPackage extends Model
{
    protected $fillable = [
        'stripe_product_id',
        'stripe_price_id',
        'name',
        'tokens_amount',
        'price',
        'is_popular',
    ];
}
