<?php

namespace App\Models\Content\Catalog;

use App\Enums\Content\Catalog\SupplyCategory;
use App\Enums\Utility\ResourceType;
use Illuminate\Database\Eloquent\Model;

class Supply extends Model
{
    protected $fillable = [
        'name',
        'description',
        'image_path',
        'price',
        'category',
        'resource',
    ];

    protected $casts = [
        'category' => SupplyCategory::class,
        'resource' => ResourceType::class,
    ];
}
