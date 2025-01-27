<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    #[\Livewire\Attributes\Url]
    public string $section = 'players';

    #[\Livewire\Attributes\Url]
    public string $playersType = 'resets';

    #[\Livewire\Attributes\Url]
    public string $guildsType = 'resets';

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
            <flux:tabs variant="segmented" wire:model="section">
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
                <flux:tabs variant="pills" wire:model="playersType"
                           class="flex overflow-auto sm:mx-0 sm:justify-center">
                    <flux:tab :accent="false" name="resets">
                        {{ __('Resets') }}
                    </flux:tab>

                    <flux:tab :accent="false" name="hof">
                        {{ __('Hall of Fame') }}
                    </flux:tab>

                    <flux:tab :accent="false" name="events">
                        {{ __('Events') }}
                    </flux:tab>

                    <flux:tab :accent="false" name="hunters">
                        {{ __('Hunters') }}
                    </flux:tab>

                    <flux:tab :accent="false" name="quests">
                        {{ __('Quests') }}
                    </flux:tab>
                </flux:tabs>

                <flux:tab.panel name="resets">
                    <livewire:pages.guest.rankings.players.filters wire:model.live="class"/>

                    <livewire:pages.guest.rankings.players.resets :$class/>
                </flux:tab.panel>

                <flux:tab.panel name="hof">
                    Hall of Fame Rankings
                </flux:tab.panel>

                <flux:tab.panel name="events">
                    Event Rankings
                </flux:tab.panel>

                <flux:tab.panel name="hunters">
                    Hunter Rankings
                </flux:tab.panel>

                <flux:tab.panel name="quests">
                    Quest Rankings
                </flux:tab.panel>
            </flux:tab.group>
        </flux:tab.panel>

        <flux:tab.panel name="guilds">
            <div class="w-full mb-8">

                <livewire:pages.guest.rankings.spotlight.guilds/>

                <flux:tab.group class="mt-8">
                    <flux:tabs variant="pills" wire:model="guildsType"
                               class="flex overflow-auto sm:mx-0 sm:justify-center">
                        <flux:tab :accent="false" name="resets">
                            {{ __('Resets') }}
                        </flux:tab>
                        <flux:tab :accent="false" name="cs">
                            {{ __('Castle Siege') }}
                        </flux:tab>
                        <flux:tab :accent="false" name="king-of-yoskreth">
                            {{ __('King of Yoskreth') }}
                        </flux:tab>
                        <flux:tab :accent="false" name="hunters">
                            {{ __('Hunters') }}
                        </flux:tab>
                    </flux:tabs>

                    <flux:tab.panel name="resets">
                        <livewire:pages.guest.rankings.guilds.filters/>

                        <livewire:pages.guest.rankings.guilds.resets :$class lazy/>
                    </flux:tab.panel>
                    <flux:tab.panel name="cs">Castle Siege Rankings</flux:tab.panel>
                    <flux:tab.panel name="king-of-yoskreth">KoY Rankings</flux:tab.panel>
                    <flux:tab.panel name="hunters">Guild Hunter Rankings</flux:tab.panel>
                </flux:tab.group>
            </div>
        </flux:tab.panel>
    </flux:tab.group>
</flux:main>
