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

{{-- TODO: EXTRACT COMPONENT FOR BIGGER HEADINGS!--}}
{{-- TODO: CREATE VARIABLE FOR COLORS (see accent in flux) !--}}

<div class="max-w-7xl mx-auto px-6 lg:px-8 py-12">
    <flux:heading level="1"
                  class="mb-3 text-center text-base font-medium text-[#10B0A9] dark:text-[#82E6FF]">
        {{ __('News') }}
    </flux:heading>

    <flux:heading class="text-center !text-3xl md:!text-4xl !font-semibold">
        {{ __('What\'s new around here?') }}
    </flux:heading>

    <flux:subheading size="xl" class="max-lg:!text-base text-center mb-12">
        {{ __('Succinct and informative updates about Lotus Mu.') }}
    </flux:subheading>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($this->articles as $article)
            <livewire:pages.guest.news.card :$article :wire:key="$article->id"/>
        @endforeach
    </div>

    {{--        Pagination--}}
    <div class="mt-8">
        <flux:pagination :paginator="$this->articles" class="!border-0"/>
    </div>
</div>
