<?php

namespace App\Models\Content;

use App\Enums\Content\ArticleType;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Translatable\HasTranslations;

class Article extends Model
{
    use HasFactory;
    use HasSlug;
    use HasTranslations;
    use HasUuids;

    protected $fillable = [
        'title',
        'content',
        'excerpt',
        'type',
        'slug',
        'image',
        'is_published',
    ];

    public array $translatable = [
        'title',
        'content',
        'excerpt',
    ];

    protected $casts = [
        'type' => ArticleType::class,
        'is_published' => 'boolean',
    ];

    public function publish(): void
    {
        $this->is_published = true;

        $this->save();
    }

    public function archive(): void
    {
        $this->is_published = false;

        $this->save();
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function getFallbackLocale(): string
    {
        return 'en';
    }

    public function scopeAvailableInLocale($query, ?string $locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        $availableLocales = [$locale];

        if ($locale !== 'en') {
            $availableLocales[] = 'en';
        }

        return $query->whereLocales('title', $availableLocales);
    }
}
