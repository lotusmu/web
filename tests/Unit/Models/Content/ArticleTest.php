<?php

use App\Enums\Content\ArticleType;
use App\Models\Content\Article;

beforeEach(function () {
    $this->article = Article::factory()->create([
        'title' => ['en' => 'Test Article'],
        'content' => ['en' => 'This is a test article content.'],
        'excerpt' => ['en' => 'Test excerpt'],
        'type' => ArticleType::NEWS,
        'is_published' => false,
    ]);
});

it('can create an article', function () {
    expect($this->article)->toBeInstanceOf(Article::class);

    $this->assertModelExists($this->article);
});

it('has translatable fields', function () {
    $locales = config('locales.available');

    foreach ($locales as $locale) {
        expect($this->article->getTranslation('title', $locale))->toBeString()
            ->and($this->article->getTranslation('content', $locale))->toBeString()
            ->and($this->article->getTranslation('excerpt', $locale))->toBeString();
    }
});

it('casts type to ArticleType enum', function () {
    expect($this->article->type)->toBeInstanceOf(ArticleType::class)
        ->and($this->article->type)->toBe(ArticleType::NEWS);
});

it('casts is_published to boolean', function () {
    expect($this->article->is_published)->toBeFalse();
});

it('can be published', function () {
    $this->article->publish();

    expect($this->article->is_published)->toBeTrue();
});

it('can be archived', function () {
    $this->article->publish();
    expect($this->article->is_published)->toBeTrue();

    $this->article->archive();
    expect($this->article->is_published)->toBeFalse();
});

it('generates a slug from the title', function () {
    expect($this->article->slug)->toBe('test-article');
});

it('can create a published article', function () {
    $publishedArticle = Article::factory()->published()->create();

    expect($publishedArticle->is_published)->toBeTrue();
});

it('can create an archived article', function () {
    $archivedArticle = Article::factory()->archived()->create();

    expect($archivedArticle->is_published)->toBeFalse();
});
