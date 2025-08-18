<flux:main container>
    <x-page-header
        :title="__('Time Your Adventures')"
        :kicker="__('Schedule')"
        :description="__('Stay ahead with real-time tracking of events and invasions across the realm.')"
    />

    <flux:tab.group class="max-w-2xl mx-auto">
        <flux:tabs variant="segmented" wire:model="tab" class="w-full">
            <flux:tab name="events">{{ __('Events') }}</flux:tab>
            <flux:tab name="invasions">{{ __('Invasions') }}</flux:tab>
        </flux:tabs>

        <flux:tab.panel name="events">
            <div class="space-y-6">
                @foreach ($this->getFilteredEvents()['events'] as $event)
                    @unless($loop->first)
                        <flux:separator variant="subtle"/>
                    @endunless

                    <livewire:pages.guest.schedule.item :$event/>
                @endforeach
            </div>
        </flux:tab.panel>

        <flux:tab.panel name="invasions">
            <div class="space-y-6">
                @foreach ($this->getFilteredEvents()['invasions'] as $event)
                    @unless($loop->first)
                        <flux:separator variant="subtle"/>
                    @endunless

                    <livewire:pages.guest.schedule.item :$event/>
                @endforeach
            </div>
        </flux:tab.panel>
    </flux:tab.group>
</flux:main>