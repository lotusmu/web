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
    }
} ?>

<flux:main container>
    <x-page-header
        title="Who's on top?"
        kicker="Rankings"
        description="The numbers don’t lie — players and guilds ranked by their achievements."
    />

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

        <flux:tab.panel name="players">

            <livewire:pages.guest.rankings.spotlight.players/>

            <flux:radio.group variant="segmented" class="max-w-xs mx-auto mt-8 cursor-pointer">
                <flux:radio value="general" label="{{ __('General') }}" checked/>
                <flux:radio value="events" label="{{ __('Events') }}"/>
                <flux:radio value="hunters" label="{{ __('Hunters') }}"/>
            </flux:radio.group>

            <flux:tab.group class="mt-8">
                <flux:tabs variant="pills" wire:model="type"
                           class="flex overflow-auto sm:mx-0 sm:justify-center">
                    <flux:tab :accent="false" name="general">
                        {{ __('General') }}
                    </flux:tab>

                    <flux:tab :accent="false" name="events">
                        {{ __('Events') }}
                    </flux:tab>

                    <flux:tab :accent="false" name="hunters">
                        {{ __('Hunters') }}
                    </flux:tab>
                </flux:tabs>
                <flux:tab.panel name="general">
                    <x-filters :filters="$this->filters"/>
                    
                    <livewire:pages.guest.rankings.players.general :filters="$this->filters"/>
                </flux:tab.panel>

                <flux:tab.panel name="events">
                    <livewire:pages.guest.rankings.players.events/>
                </flux:tab.panel>

                <flux:tab.panel name="hunters">
                    <livewire:pages.guest.rankings.players.hunters/>
                </flux:tab.panel>
            </flux:tab.group>
        </flux:tab.panel>

        <flux:tab.panel name="guilds">
            <div class="w-full mb-8">

                <livewire:pages.guest.rankings.spotlight.guilds/>

                <livewire:pages.guest.rankings.guilds.filters/>

                <livewire:pages.guest.rankings.guilds.table lazy/>
            </div>
        </flux:tab.panel>
    </flux:tab.group>
</flux:main>
