<div class="space-y-12">
    <header>
        <flux:heading size="lg">
            {{ $type->rulesHeading() }}
        </flux:heading>

        <flux:subheading>
            {{ $type->rulesDescription() }}
        </flux:subheading>
    </header>

    <div>
        @foreach($this->rules as $rule)
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-3">
                    @if($rule['image'])
                        <img src="{{ $rule['image'] }}"
                             alt="{{ $rule['name'] }}"
                             class="w-12 h-12 object-cover">
                    @endif

                    <flux:text>
                        {{ $rule['name'] }}
                    </flux:text>
                </div>

                <flux:badge size="sm" variant="solid">
                    {{ $rule['points'] }} {{ __('points') }}
                </flux:badge>
            </div>

            @if(!$loop->last)
                <flux:separator variant="subtle" class="my-6"/>
            @endif
        @endforeach
    </div>
</div>