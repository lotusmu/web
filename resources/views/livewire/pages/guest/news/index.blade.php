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
    use WithPagination;

    #[Computed]
    public function articles(): LengthAwarePaginator|_IH_Article_C|array
    {
        return Article::where('is_published', true)
            ->where('type', ArticleType::NEWS)
            ->orderBy('created_at', 'desc')
            ->paginate(15);
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

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($this->articles as $article)
            <livewire:pages.guest.news.card :$article :wire:key="$article->id"/>
        @endforeach
    </div>

    {{--        Pagination--}}
    <div>
        <flux:pagination :paginator="$this->articles" class="!border-0"/>
    </div>
</div>
