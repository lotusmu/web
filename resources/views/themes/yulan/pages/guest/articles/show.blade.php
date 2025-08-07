@php
use App\Enums\Content\ArticleType;
@endphp

<div class="max-w-7xl mx-auto px-6 lg:px-8 py-12 space-y-12">
    <x-page-header
        :title="__('What\'s new around here?')"
        :kicker="__('News')"
        :description="__('Succinct and informative updates about Lotus Mu.')"
    />

    <flux:link variant="subtle" icon="arrow-left"
               wire:navigate
               href="{{ route('articles', ['tab' => $this->article->type === ArticleType::PATCH_NOTE ? 'updates' : 'news']) }}"
               class="flex items-center gap-2 text-sm">
        <flux:icon.arrow-left variant="micro"/>
        {{ __('Back to all ' . ($this->article->type === ArticleType::PATCH_NOTE ? 'updates' : 'news')) }}
    </flux:link>

    @themeComponent('article.preview', ['article' => $this->article])
</div>