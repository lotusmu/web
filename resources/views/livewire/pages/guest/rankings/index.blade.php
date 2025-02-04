<?php

use App\Livewire\Forms\Filters;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Reactive;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public Filters $filters;

    #[\Livewire\Attributes\Url]
    public string $tab = 'players';

    #[\Livewire\Attributes\Url]
    public string $type = 'general';

    public function mount()
    {
        $this->filters->init();

        if ( ! in_array($this->tab, ['players', 'guilds'])) {
            $this->tab = 'players';
        }

        if ( ! in_array($this->type, ['general', 'weekly'])) {
            $this->type = 'general';
        }
    }
} ?>

<flux:main container>
    <x-page-header
        title="Who's on top?"
        kicker="Rankings"
        description="The numbers don’t lie — players and guilds ranked by their achievements."
    />

    <flux:modal name="events-scoring" variant="flyout" position="right">
        <livewire:pages.guest.rankings.scoring-rules.events lazy/>
    </flux:modal>

    <flux:modal name="hunters-scoring" variant="flyout" position="right">
        <livewire:pages.guest.rankings.scoring-rules.hunters lazy/>
    </flux:modal>

    <flux:tab.group>
        <div class="flex justify-end items-center">
            <flux:tabs variant="segmented" wire:model="tab">
                <flux:tab name="players">
                    {{ __('Players') }}
                </flux:tab>
                <flux:tab name="guilds">
                    {{ __('Guilds') }}
                </flux:tab>
            </flux:tabs>
        </div>

        <flux:tab.panel name="players" class="space-y-8">
            <livewire:pages.guest.rankings.spotlight.players/>

            <flux:tab.group>
                <div class="flex justify-center">
                    <flux:tabs variant="segmented" class="w-full max-w-xs mb-8" wire:model="type">
                        <flux:tab name="general">{{ __('General') }}</flux:tab>
                        <flux:tab name="weekly">{{ __('Weekly') }}</flux:tab>
                    </flux:tabs>
                </div>

                <x-rankings.filters :filters="$this->filters"/>

                <flux:tab.panel name="general">
                    <livewire:pages.guest.rankings.players.general :filters="$this->filters" lazy/>
                </flux:tab.panel>

                <flux:tab.panel name="weekly">
                    <livewire:pages.guest.rankings.players.weekly :filters="$this->filters" lazy/>
                </flux:tab.panel>
            </flux:tab.group>
        </flux:tab.panel>

        <flux:tab.panel name="guilds">
            <div class="w-full space-y-8">
                <livewire:pages.guest.rankings.spotlight.guilds/>

                <livewire:pages.guest.rankings.guilds-table lazy/>
            </div>
        </flux:tab.panel>
    </flux:tab.group>
</flux:main>
