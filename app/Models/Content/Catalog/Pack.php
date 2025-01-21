<?php

namespace App\Models\Content\Catalog;

use App\Enums\Content\Catalog\PackTier;
use App\Enums\Game\CharacterClass;
use App\Enums\Utility\ResourceType;
use Illuminate\Database\Eloquent\Model;

class Pack extends Model
{
    protected $fillable = [
        'character_class',
        'tier',
        'image_path',
        'has_level',
        'level',
        'has_additional',
        'additional',
        'has_luck',
        'has_skill',
        'price',
        'resource',
    ];

    protected $casts = [
        'character_class' => CharacterClass::class,
        'tier' => PackTier::class,
        'resource' => ResourceType::class,
        'has_level' => 'boolean',
        'has_additional' => 'boolean',
        'has_luck' => 'boolean',
        'has_skill' => 'boolean',
        'level' => 'integer',
        'additional' => 'integer',
        'price' => 'integer',
    ];
}
