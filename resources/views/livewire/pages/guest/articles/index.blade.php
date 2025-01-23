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

<flux:main container>
    <x-page-header
        title="What's new around here?"
        kicker="News"
        description="Succinct and informative updates about Lotus Mu."
    />

    <flux:tab.group>
        <flux:tabs wire:model="tab" class="max-lg:mx-auto max-lg:max-w-[40rem]">
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
</flux:main>
