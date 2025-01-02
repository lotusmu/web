<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use App\Models\Content\Article;

new #[Layout('layouts.guest')] class extends Component {
    public Article $article;

    public function mount(Article $article)
    {
        $this->article = $article;
    }
}; ?>

<main class="max-w-4xl mx-auto px-6 py-12">
    <article class="space-y-8">
        <!-- Header -->
        <header class="flex items-center max-sm:flex-col-reverse max-sm:items-start max-sm:gap-4">
            <div>
                <flux:heading size="xl" level="1">
                    {{ $article->title }}
                </flux:heading>

                <x-flux::subheading>
                    <time datetime="{{ $article->created_at->toDateString() }}">
                        {{ $article->created_at->format('M d, Y') }}
                    </time>
                </x-flux::subheading>
            </div>

            <flux:spacer/>

            <flux:button :href="route('news')"
                         wire:navigate
                         inset="left"
                         variant="ghost" size="sm" icon="arrow-left">
                {{__('Back to News')}}
            </flux:button>
        </header>

        <!-- Featured Image -->
        <div class="aspect-video overflow-hidden rounded-xl">
            <img
                src="{{ $article->image ? Storage::url($article->image) : 'https://placehold.co/600x400/EEE/31343C?font=montserrat&text=Lotus Mu' }}"
                alt="{{ $article->title }}"
                class="w-full h-full object-cover">
        </div>

        <!-- Content -->
        <x-prose :content="$article->content"/>
    </article>
</main>
