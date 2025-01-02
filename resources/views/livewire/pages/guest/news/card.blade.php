<?php

use Livewire\Volt\Component;
use App\Models\Content\Article;

new class extends Component {
    public Article $article;
}; ?>

<article class="relative group h-full">
    <a href="{{ route('news.show', $article) }}" wire:navigate class="block relative flex-1 group">
        <div
            class="min-h-[250px] relative w-full h-full overflow-hidden flex flex-col text-left bg-cover bg-top bg-no-repeat dark:border-b-2 border-zinc-200 dark:border-zinc-700 shadow-lg dark:shadow-none px-4 py-3 transition-all duration-200 ease-in-out cursor-pointer dark:hover:shadow-none hover:shadow-2xl dark:hover:border-b-2 dark:hover:border-zinc-50 hover:scale-[0.98]"
            style="background-image: url('{{ $article->image ? Storage::url($article->image) : 'https://placehold.co/600x400/EEE/31343C?font=montserrat&text=Lotus Mu' }}')">

            <!-- Gradient overlay -->
            <div
                class="absolute inset-0 pointer-events-none bg-gradient-to-t from-black/70 via-black/40 dark:from-black dark:via-black/70 to-transparent opacity-90"></div>

            <!-- Overlay on hover -->
            <div
                class="absolute inset-0 bg-black opacity-0 transition-opacity duration-200 group-hover:opacity-60 dark:group-hover:opacity-75"></div>

            <!-- Content -->
            <div class="mt-auto z-0">
                <flux:subheading size="lg" class="!text-white/70">
                    {{ $article->created_at->format('F j, Y') }}
                </flux:subheading>

                <flux:heading level="3" class="!font-black !text-xl !text-white">
                    {{$article->title}}
                </flux:heading>

                <flux:text
                    class="h-0 opacity-0 translate-y-5 transition-all duration-500 ease-in-out group-hover:h-16 group-hover:opacity-100 group-hover:translate-y-0 !text-white/70">
                    {{$article->excerpt}}
                </flux:text>
            </div>
        </div>
    </a>
</article>
