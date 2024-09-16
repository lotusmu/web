<?php

use App\Enums\Content\ArticleType;
use App\Filament\Resources\ArticleResource;
use App\Models\Content\Article;
use Illuminate\Database\Eloquent\Factories\Sequence;

use function Pest\Livewire\livewire;

it('can render index page', function () {
    $this->get(ArticleResource::getUrl('index'))->assertSuccessful();
});

it('can render create page', function () {
    $this->get(ArticleResource::getUrl('create'))->assertSuccessful();
});

it('can render edit page', function () {
    $article = Article::factory()->create();
    $this->get(ArticleResource::getUrl('edit', ['record' => $article]))->assertSuccessful();
});

it('can list articles', function () {
    $articles = Article::factory()->count(5)->create();

    livewire(ArticleResource\Pages\ListArticles::class)
        ->assertCanSeeTableRecords($articles);
});

it('can sort articles by creation date', function () {
    $articles = Article::factory()
        ->count(2)
        ->state(new Sequence(
            ['created_at' => now()->subDay()],
            ['created_at' => now()]
        ))
        ->create();

    livewire(ArticleResource\Pages\ListArticles::class)
        ->assertCanSeeTableRecords($articles->sortBy('created_at'))
        ->assertCanSeeTableRecords($articles->sortByDesc('created_at'));
});

it('can create article', function () {
    $newArticle = Article::factory()->archived()->make();

    livewire(ArticleResource\Pages\CreateArticle::class)
        ->fillForm([
            'title' => $newArticle->title,
            'type' => $newArticle->type,
            'is_published' => $newArticle->is_published,
            'excerpt' => $newArticle->excerpt,
            'content' => $newArticle->content,
        ])
        ->assertActionExists('create')
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('articles', [
        'title->en' => $newArticle->title,
        'type' => $newArticle->type,
        'is_published' => $newArticle->is_published ? 1 : 0,
        'excerpt->en' => $newArticle->excerpt,
        'content->en' => $newArticle->content,
    ]);
});

it('can publish article', function () {
    $article = Article::factory()->create(['is_published' => false]);

    livewire(ArticleResource\Pages\ListArticles::class)
        ->callTableAction('publish', $article)
        ->assertHasNoActionErrors();

    expect($article->refresh()->is_published)->toBeTrue();
});

it('can archive article', function () {
    $article = Article::factory()->create(['is_published' => true]);

    livewire(ArticleResource\Pages\ListArticles::class)
        ->callTableAction('archive', $article)
        ->assertHasNoActionErrors();

    expect($article->refresh()->is_published)->toBeFalse();
});

it('has correct tabs and filters articles', function () {
    $newsArticle = Article::factory()->create(['type' => ArticleType::NEWS]);
    $patchNoteArticle = Article::factory()->create(['type' => ArticleType::PATCH_NOTE]);

    $component = livewire(ArticleResource\Pages\ListArticles::class);

    $component->assertCanSeeTableRecords([$newsArticle, $patchNoteArticle]);

    $component->set('activeTab', 'news')
        ->assertCanSeeTableRecords([$newsArticle])
        ->assertCanNotSeeTableRecords([$patchNoteArticle]);

    $component->set('activeTab', 'patch_notes')
        ->assertCanSeeTableRecords([$patchNoteArticle])
        ->assertCanNotSeeTableRecords([$newsArticle]);
});
