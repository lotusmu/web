<?php

namespace App\Livewire\Pages\Guest\Articles;

use App\Enums\Content\ArticleType;
use App\Livewire\BaseComponent;
use App\Models\Content\Article;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;

class Feed extends BaseComponent
{
    use WithPagination;

    public ArticleType $type;

    #[Computed]
    public function articles(): LengthAwarePaginator
    {
        return Article::availableInLocale()
            ->where('is_published', true)
            ->where('type', $this->type)
            ->orderBy('created_at', 'desc')
            ->paginate(5);
    }

    protected function getViewName(): string
    {
        return 'pages.guest.articles.feed';
    }

    protected function getLayoutType(): string
    {
        return 'guest';
    }
}