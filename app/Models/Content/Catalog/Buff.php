<?php

namespace App\Models\Content\Catalog;

use App\Enums\Utility\ResourceType;
use Illuminate\Database\Eloquent\Model;

class Buff extends Model
{
    protected $fillable = [
        'name',
        'stats',
        'image_path',
        'duration_prices',
        'resource',
        'is_bundle',
        'bundle_items',
    ];

    protected $casts = [
        'stats' => 'array',
        'duration_prices' => 'array',
        'is_bundle' => 'boolean',
        'bundle_items' => 'array',
        'resource' => ResourceType::class,
    ];
}
