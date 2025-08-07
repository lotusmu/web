
<div class="pt-12">
    @if($this->articles->count() > 0)
        @foreach($this->articles as $article)
            @if(!$loop->first)
                <flux:separator variant="subtle" class="my-16"/>
            @endif

            @themeComponent('article.preview', ['article' => $article])
        @endforeach
    @else
        <div>
            <flux:heading>{{__('No articles found.')}}</flux:heading>
            <flux:subheading>{{__('There are currently no published articles in this category.')}}</flux:subheading>
        </div>
    @endif

    <div>
        <flux:pagination :paginator="$this->articles" class="!border-0"/>
    </div>
</div>
