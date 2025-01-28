<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    #[\Livewire\Attributes\Url]
    public string $tab = 'players';

    #[\Livewire\Attributes\Url]
    public string $type = 'general';

    #[\Livewire\Attributes\Url]
    public string $class = 'all';
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
                    <livewire:pages.guest.rankings.players.filters wire:model.live="class"/>

                    <livewire:pages.guest.rankings.players.general :$class/>
                </flux:tab.panel>

                <flux:tab.panel name="events">
                    <livewire:pages.guest.rankings.players.filters wire:model.live="class"/>

                    <livewire:pages.guest.rankings.players.events :$class/>
                </flux:tab.panel>

                <flux:tab.panel name="hunters">
                    <livewire:pages.guest.rankings.players.filters wire:model.live="class"/>

                    <livewire:pages.guest.rankings.players.hunters :$class/>
                </flux:tab.panel>
            </flux:tab.group>
        </flux:tab.panel>

        <flux:tab.panel name="guilds">
            <div class="w-full mb-8">

                <livewire:pages.guest.rankings.spotlight.guilds/>

                <livewire:pages.guest.rankings.guilds.filters/>

                <livewire:pages.guest.rankings.guilds.resets :$class lazy/>
            </div>
        </flux:tab.panel>
    </flux:tab.group>
</flux:main>
