<?php

namespace App\Livewire\Pages\Guest;

use App\Enums\Content\ArticleType;
use App\Livewire\BaseComponent;
use App\Models\Content\Article;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;

class Home extends BaseComponent
{
    #[Computed]
    public function articles(): Collection
    {
        return Article::where('is_published', true)
            ->where('type', ArticleType::NEWS)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
    }

    protected function getViewName(): string
    {
        return 'pages.guest.home.index';
    }

    protected function getLayoutType(): string
    {
        return 'guest';
    }
}
