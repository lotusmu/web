@props(['articles'])

<section class="relative isolate !-mt-24">
    <x-home.wrapper>
        <div class="flex gap-8 items-center mb-8">
            <flux:heading size="xl" level="2" class="z-0">
                {{ __('Latest News') }}
            </flux:heading>

            <flux:link variant="subtle" :href="route('articles')" wire:navigate class="flex items-center gap-2 z-0">
                {{ __('View All') }}
                <flux:icon.arrow-right variant="micro"/>
            </flux:link>
        </div>

        <!-- News Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($articles as $article)
                <livewire:pages.guest.articles.card :$article :wire:key="$article->id"/>
            @endforeach
        </div>
    </x-home.wrapper>
</section>
