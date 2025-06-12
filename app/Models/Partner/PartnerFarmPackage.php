<?php

namespace App\Models\Partner;

use App\Enums\Partner\PartnerLevel;
use Illuminate\Database\Eloquent\Model;

class PartnerFarmPackage extends Model
{
    protected $fillable = [
        'name',
        'partner_level',
        'items',
        'is_active',
    ];

    protected $casts = [
        'partner_level' => 'integer',
        'items' => 'array',
        'is_active' => 'boolean',
    ];

    public function getPartnerLevelEnumAttribute(): PartnerLevel
    {
        return PartnerLevel::from($this->partner_level);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForLevel($query, PartnerLevel $level)
    {
        return $query->where('partner_level', $level->value);
    }
}
