<flux:column>{{ __('Character') }}</flux:column>
<flux:column>{{ __('Class') }}</flux:column>
<flux:column>{{ __('Level') }}</flux:column>
<flux:column>{{ __('Resets') }}</flux:column>
<flux:column>{{ __('Guild') }}</flux:column>
<flux:column>{{ __('Weekly Hunt Score') }}</flux:column>
<flux:column>
    <div class="flex items-center gap-2">
        <span>{{ __('Total Hunt Score') }}</span>
        <livewire:pages.guest.rankings.scoring-rules-modal type="hunters" wire:key="hunters"/>
    </div>
</flux:column>
