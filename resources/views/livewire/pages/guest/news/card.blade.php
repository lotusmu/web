<?php

use Livewire\Volt\Component;
use App\Models\Content\Article;

new class extends Component {
    public Article $article;
}; ?>

<article class="relative group h-full">
    <a href="{{ route('news.show', $article) }}" class="absolute inset-0 z-10">
        <span class="sr-only">Read full article about {{$article->title}}</span>
    </a>
    <flux:card class="overflow-hidden !p-0 h-full flex flex-col">
        <div class="relative overflow-hidden">
            <img src="{{ $article->image ? Storage::url($article->image) : 'https://placehold.co/400x200' }}"
                 alt="News thumbnail"
                 class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105">
            <div
                class="absolute inset-0 bg-black opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
        </div>
        <div class="p-6 flex-1">
            <flux:subheading>{{ $article->created_at->format('M d, Y') }}</flux:subheading>
            <flux:heading size="lg" level="2" accent>
                {{$article->title}}
            </flux:heading>
            <flux:text>
                {{$article->excerpt}}
            </flux:text>
        </div>
    </flux:card>
</article>
