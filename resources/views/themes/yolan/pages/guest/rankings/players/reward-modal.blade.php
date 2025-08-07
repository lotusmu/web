@php
use App\Enums\Utility\ResourceType;
@endphp

<div class="space-y-6">
    <header>
        <flux:heading size="lg">
            {{ __('Weekly Rankings Rewards') }}
        </flux:heading>
        <flux:subheading>
            {{ __('Rewards are distributed every Sunday at 23:59 server time.') }}
        </flux:subheading>
    </header>

    <flux:table>
        <flux:columns>
            <flux:column>
                {{ __('Rank') }}
            </flux:column>

            <flux:column>
                {{ __('Reward') }}
            </flux:column>
        </flux:columns>

        <flux:rows>
            @foreach($this->rewards as $reward)
                <flux:row>
                    <flux:cell>
                        {{ $reward['position'] }}
                    </flux:cell>

                    <flux:cell class="space-x-1">
                        @foreach($reward['rewards'] as $item)
                            <x-resource-badge
                                :value="$item['amount']"
                                :resource="ResourceType::from($item['type'])"
                            />
                        @endforeach
                    </flux:cell>
                </flux:row>
            @endforeach
        </flux:rows>
    </flux:table>

    <div class="flex items-center gap-1">
        <flux:icon.information-circle variant="mini" inset="top bottom"/>
        <flux:text size="sm">
            {{ __('Rankings reset immediately after rewards distribution.') }}
        </flux:text>
    </div>
</div>