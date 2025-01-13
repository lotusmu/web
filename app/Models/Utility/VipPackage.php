<?php

namespace App\Models\Utility;

use App\Enums\Game\AccountLevel;
use Illuminate\Database\Eloquent\Model;

class VipPackage extends Model
{
    protected $fillable = ['level', 'duration', 'cost', 'is_best_value', 'sort_order'];

    protected $casts = [
        'level' => AccountLevel::class,
        'is_best_value' => 'boolean',
    ];

    public function getCatalogOrderAttribute(): int
    {
        return cache()->remember(
            "vip_package.{$this->id}.catalog_order",
            now()->addDay(),
            function () {
                return match ($this->level) {
                    AccountLevel::Bronze => 1,
                    AccountLevel::Gold => 2,
                    AccountLevel::Silver => 3,
                    default => 99
                };
            }
        );
    }
}
