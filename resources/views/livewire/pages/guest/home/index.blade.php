<?php

use App\Enums\Content\ArticleType;
use App\Models\Content\Article;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    #[Computed]
    public function articles(): Collection
    {
        return Article::where('is_published', true)
            ->where('type', ArticleType::NEWS)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
    }
}; ?>

<div class="space-y-40">
    <x-sections.hero/>

    <x-sections.news :articles="$this->articles"/>

    <x-sections.features/>

    <x-sections.more-features/>

    <x-sections.cta/>
</div>
