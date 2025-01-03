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

<div class="max-w-7xl max-lg:max-w-[40rem] mx-auto px-6 lg:px-8 py-12 space-y-12">
    <x-news-header/>

    <flux:tab.group class="">
        <flux:tabs wire:model="tab">
            <flux:tab name="news">News</flux:tab>
            <flux:tab name="updates">Updates</flux:tab>
        </flux:tabs>

        <flux:tab.panel name="news">
            <livewire:pages.guest.articles.feed :type="ArticleType::NEWS"/>
        </flux:tab.panel>

        <flux:tab.panel name="updates">
            <livewire:pages.guest.articles.feed :type="ArticleType::PATCH_NOTE"/>
        </flux:tab.panel>
    </flux:tab.group>
</div>
