<?php

use App\Enums\Content\ArticleType;
use Livewire\Volt\Component;
use App\Models\Content\Article;
use Livewire\Attributes\Computed;

new class extends Component {
    #[Computed]
    public function articles()
    {
        return Article::where('is_published', true)
            ->where('type', ArticleType::NEWS)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
    }
}; ?>

<section class="max-w-7xl mx-auto px-6 lg:px-8 !-mt-24">
    <div class="flex gap-8 items-center mb-8">
        <flux:heading size="xl" level="2" class="z-0">
            {{ __('Latest News') }}
        </flux:heading>
        <flux:link variant="subtle" :href="route('articles.index')" wire:navigate class="flex items-center gap-2 z-0">
            {{ __('View All') }}
            <flux:icon.arrow-right variant="micro"/>
        </flux:link>
    </div>

    <!-- News Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($this->articles as $article)
            <livewire:pages.guest.articles.card :$article :wire:key="$article->id"/>
        @endforeach
    </div>
</section>
