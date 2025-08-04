<?php

namespace App\Livewire\Pages\Guest\Articles;

use App\Enums\Content\ArticleType;
use App\Livewire\BaseComponent;
use Livewire\Attributes\Url;

class Articles extends BaseComponent
{
    #[Url]
    public string $tab = 'news';

    protected function getViewName(): string
    {
        return 'pages.guest.articles.index';
    }

    protected function getLayoutType(): string
    {
        return 'guest';
    }
}