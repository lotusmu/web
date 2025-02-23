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
    <x-home.sections.hero/>

    <x-home.sections.news :articles="$this->articles"/>

    <x-home.sections.features/>

    <x-home.sections.more-features/>

    <x-home.sections.catalog/>

    <x-home.sections.cta/>
</div>
