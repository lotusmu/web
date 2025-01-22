<?php

namespace App\Models\Content\Catalog;

use App\Enums\Content\Catalog\EquipmentOption;
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
        'tooltip_image_path',
        'contents',
        'has_level',
        'level',
        'has_additional',
        'additional',
        'has_luck',
        'has_skill',
        'has_excellent',
        'excellent_options',
        'price',
        'resource',
    ];

    protected $casts = [
        'character_class' => CharacterClass::class,
        'tier' => PackTier::class,
        'contents' => 'array',
        'resource' => ResourceType::class,
        'has_level' => 'boolean',
        'has_additional' => 'boolean',
        'has_luck' => 'boolean',
        'has_skill' => 'boolean',
        'has_excellent' => 'boolean',
        'excellent_options' => 'array',
        'level' => 'integer',
        'additional' => 'integer',
        'price' => 'integer',
    ];

    public function hasOption(EquipmentOption $option): bool
    {
        return match ($option) {
            EquipmentOption::LEVEL => $this->has_level && $this->level > 0,
            EquipmentOption::ADDITIONAL => $this->has_additional && $this->additional > 0,
            EquipmentOption::LUCK => $this->has_luck,
            EquipmentOption::WEAPON_SKILL => $this->has_skill,
            EquipmentOption::EXCELLENT => $this->has_excellent,
        };
    }

    public function getOptionValue(EquipmentOption $option): ?string
    {
        if (! $this->hasOption($option)) {
            return null;
        }

        return match ($option) {
            EquipmentOption::LEVEL => $this->level,
            EquipmentOption::ADDITIONAL => $this->additional,
            default => null
        };
    }

    public function getOptionDisplayText(EquipmentOption $option): string|array
    {
        if (! $this->hasOption($option)) {
            return '';
        }

        return match ($option) {
            EquipmentOption::ADDITIONAL => "{$option->getLabel()} +{$this->additional}",
            EquipmentOption::LEVEL => "{$option->getLabel()} +{$this->level}",
            EquipmentOption::EXCELLENT => collect($this->excellent_options)
                ->pluck('option')
                ->toArray(),
            default => $option->getLabel()
        };
    }
}
