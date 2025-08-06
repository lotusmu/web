@php
    use App\Enums\Content\ArticleType;
@endphp

<flux:main container>
    <x-page-header
        :title="__('What\'s new around here?')"
        :kicker="__('News')"
        :description="__('Succinct and informative updates about Lotus Mu.')"
    />

    <flux:tab.group>
        <flux:tabs wire:model="tab" class="max-lg:mx-auto max-lg:max-w-[40rem]">
            <flux:tab name="news">{{__('News')}}</flux:tab>
            <flux:tab name="updates">{{__('Updates')}}</flux:tab>
        </flux:tabs>

        <flux:tab.panel name="news">
            <livewire:pages.guest.articles.feed :type="ArticleType::NEWS"/>
        </flux:tab.panel>

        <flux:tab.panel name="updates">
            <livewire:pages.guest.articles.feed :type="ArticleType::PATCH_NOTE"/>
        </flux:tab.panel>
    </flux:tab.group>
</flux:main>
