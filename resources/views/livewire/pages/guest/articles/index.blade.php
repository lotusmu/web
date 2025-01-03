<?php

use App\Enums\Content\ArticleType;
use Illuminate\Pagination\LengthAwarePaginator;
use LaravelIdea\Helper\App\Models\Content\_IH_Article_C;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use App\Models\Content\Article;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;

new #[Layout('layouts.guest')] class extends Component {
    #[\Livewire\Attributes\Url]
    public string $tab = 'news';
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

    <flux:tab.group>
        <flux:tabs wire:model="tab">
            <flux:tab name="news">News</flux:tab>
            <flux:tab name="updates">Updates</flux:tab>
        </flux:tabs>

        <flux:tab.panel name="news">
            <livewire:pages.guest.articles.news/>
        </flux:tab.panel>

        <flux:tab.panel name="updates">
            <livewire:pages.guest.articles.updates/>
        </flux:tab.panel>
    </flux:tab.group>
</div>
