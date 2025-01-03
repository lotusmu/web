<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use App\Models\Content\Article;
use App\Enums\Content\ArticleType;

new #[Layout('layouts.guest')] class extends Component {
    public Article $article;
    public string $tab = '';

    public function mount(Article $article)
    {
        $this->article = $article;
    }
}; ?>

<div class="max-w-7xl mx-auto px-6 lg:px-8 py-12 space-y-12">
    <x-news-header/>

    <flux:link variant="subtle" icon="arrow-left"
               wire:navigate
               href="{{ route('articles.index', ['tab' => $this->article->type === ArticleType::PATCH_NOTE ? 'updates' : 'news']) }}"
               class="flex items-center gap-2 text-sm">
        <flux:icon.arrow-left variant="micro"/>
        {{ __('Back to all ' . ($this->article->type === ArticleType::PATCH_NOTE ? 'updates' : 'news')) }}
    </flux:link>

    <livewire:pages.guest.articles.preview :article="$this->article"/>
</div>
