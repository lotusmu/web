<?php

use App\Enums\Content\ArticleType;
use Illuminate\Pagination\LengthAwarePaginator;
use LaravelIdea\Helper\App\Models\Content\_IH_Article_C;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use App\Models\Content\Article;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public ArticleType $type;

    #[Computed]
    public function articles(): LengthAwarePaginator|_IH_Article_C|array
    {
        return Article::where('is_published', true)
            ->where('type', $this->type)
            ->orderBy('created_at', 'desc')
            ->paginate(5);
    }
}; ?>

<div class="pt-12">
    @if($this->articles->count() > 0)
        @foreach($this->articles as $article)
            @if(!$loop->first)
                <flux:separator variant="subtle" class="my-16"/>
            @endif

            <livewire:pages.guest.articles.preview :$article :wire:key="$article->id"/>
        @endforeach
    @else
        <div>
            <flux:heading>No articles found.</flux:heading>
            <flux:subheading>There are currently no published articles in this category.</flux:subheading>
        </div>
    @endif

    <div>
        <flux:pagination :paginator="$this->articles" class="!border-0"/>
    </div>
</div>
