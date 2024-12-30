<?php

namespace App\Models\Utility;

use App\Enums\Utility\UpdateBannerType;
use Illuminate\Database\Eloquent\Model;

class UpdateBanner extends Model
{
    protected $casts = [
        'is_active' => 'boolean',
        'type' => UpdateBannerType::class,
    ];

    protected $fillable = [
        'type',
        'content',
        'url',
        'is_active',
    ];

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if ($model->is_active) {
                // Deactivate all other banners except this one
                static::query()
                    ->where('id', '!=', $model->id)
                    ->update(['is_active' => false]);
            }
        });
    }

    public static function getActive()
    {
        return static::where('is_active', true)->first();
    }
}
