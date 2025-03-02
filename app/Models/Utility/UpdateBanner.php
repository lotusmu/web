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

    public static function boot(): void
    {
        parent::boot();

        static::saving(function ($model) {
            if (! $model->is_active) {
                return;
            }

            $isAnnouncement = $model->type === UpdateBannerType::ANNOUNCEMENT;

            static::query()
                ->where('id', '!=', $model->id)
                ->where('type', $isAnnouncement ? UpdateBannerType::ANNOUNCEMENT : '!=', UpdateBannerType::ANNOUNCEMENT)
                ->update(['is_active' => false]);
        });

        static::saved(function ($model) {
            if ($model->type === UpdateBannerType::ANNOUNCEMENT) {
                cache()->forget("banner_type_{$model->type->value}");
            } else {
                cache()->forget('active_updates_banner');
            }
        });

        static::deleted(function ($model) {
            if ($model->type === UpdateBannerType::ANNOUNCEMENT) {
                cache()->forget("banner_type_{$model->type->value}");
            } else {
                cache()->forget('active_updates_banner');
            }
        });
    }
}
