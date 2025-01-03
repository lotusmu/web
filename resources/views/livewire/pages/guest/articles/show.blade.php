<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use App\Models\Content\Article;

new #[Layout('layouts.guest')] class extends Component {
    public Article $article;
    public string $tab = '';

    public function mount(Article $article)
    {
        $this->article = $article;
    }
}; ?>

<div class="max-w-7xl mx-auto px-6 lg:px-8 py-12 space-y-12">
    <header class="flex flex-col items-center text-center">
        <flux:heading level="1" size="lg" class="!text-[var(--color-compliment-content)] mb-3">
            {{ __('News') }}
        </flux:heading>

        <flux:heading size="2xl">
            {{ __('What\'s new around here?') }}
        </flux:heading>

        <flux:subheading size="lg">
            {{ __('Succinct and informative updates about Lotus Mu.') }}
        </flux:subheading>
    </header>

    <flux:link variant="subtle" icon="arrow-left"
               href="{{ route('articles.index') }}" wire:navigate
               class="flex items-center gap-2 text-sm">
        <flux:icon.arrow-left variant="micro"/>
        {{ __('Back to all news') }}
    </flux:link>

    <livewire:pages.guest.articles.preview :article="$this->article"/>
</div>
