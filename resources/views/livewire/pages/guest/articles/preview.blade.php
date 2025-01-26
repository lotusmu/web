<?php

use Livewire\Volt\Component;
use App\Models\Content\Article;

new class extends Component {
    public Article $article;
}; ?>

<article class="pb-8 max-lg:mx-auto max-w-[40rem] lg:grid lg:grid-cols-[18rem_40rem_1fr]">
    <div>
        <flux:subheading class="max-lg:mb-2">
            {{ $article->created_at->format('F j, Y') }}
        </flux:subheading>
    </div>

    <div class="space-y-6">
        <a href="{{ route('articles.show', $article) }}" wire:navigate>
            <flux:heading level="2" size="xl">
                {{ $article->title }}
            </flux:heading>
        </a>

        @if($article->excerpt)
            <flux:subheading>
                {{ $article->excerpt }}
            </flux:subheading>
        @endif

        <!-- Featured Image -->
        @if($article->image)
            <img
                src="{{ Storage::url($article->image) }}"
                alt="{{ $article->title }} preview image"
                class="rounded-xl border border-zinc-200 dark:border-white/10">
        @endif

        <!-- Content -->
        <x-prose :content="$article->content" class="!mt-16"/>
    </div>
</article>
