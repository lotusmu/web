<?php

namespace App\Models;

use App\Enums\ArticleType;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Translatable\HasTranslations;

class Article extends Model
{
    use HasFactory, HasSlug, HasTranslations, HasUuids;

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

    public static function getForm()
    {
        return [
            TextInput::make('title')
                ->required(),
            Textarea::make('excerpt')
                ->required()
                ->hint('A short summary of the article for preview'),
            RichEditor::make('content')
                ->required(),
            Select::make('type')
                ->options(ArticleType::class)
                ->enum(ArticleType::class)
                ->required(),
            FileUpload::make('image')
                ->image()
                ->imageEditor()
                ->directory('article-images')
                ->visibility('public'),
            Toggle::make('is_published')
                ->hint('Publish article status')
                ->label('Published')
                ->default(false),
        ];
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }
}
