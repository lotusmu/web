<?php

namespace App\Livewire\Pages\Guest\Articles;

use App\Livewire\BaseComponent;
use App\Models\Content\Article;

class Show extends BaseComponent
{
    public Article $article;

    public string $tab = '';

    public function mount(Article $article)
    {
        $this->article = $article;
    }

    protected function getViewName(): string
    {
        return 'pages.guest.articles.show';
    }

    protected function getLayoutType(): string
    {
        return 'guest';
    }
}
