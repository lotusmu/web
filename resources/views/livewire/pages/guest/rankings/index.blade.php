<?php

use App\Enums\Utility\RankingType;
use App\Livewire\Forms\Filters;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Reactive;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public Filters $filters;

    #[\Livewire\Attributes\Url]
    public string $tab = 'players';

    #[\Livewire\Attributes\Url]
    public RankingType $type = RankingType::GENERAL;

    public function mount()
    {
        $this->filters->init();
    }

    public function isType(RankingType $type): bool
    {
        return $this->type === $type;
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

        <flux:tab.panel name="players" class="space-y-8">
            <livewire:pages.guest.rankings.spotlight.players/>

            <flux:radio.group variant="segmented" wire:model.live="type"
                              class="max-sm:max-w-none max-w-xs mx-auto cursor-pointer">
                @foreach(RankingType::cases() as $type)
                    <flux:radio :value="$type->value" :label="$type->label()"/>
                @endforeach
            </flux:radio.group>

            <x-rankings.filters :filters="$this->filters"/>

            <livewire:pages.guest.rankings.players-table :filters="$this->filters"
                                                         :type="$this->type"/>
        </flux:tab.panel>

        <flux:tab.panel name="guilds">
            <div class="w-full space-y-8">
                <livewire:pages.guest.rankings.spotlight.guilds/>

                <x-rankings.search/>

                <livewire:pages.guest.rankings.guilds-table lazy/>
            </div>
        </flux:tab.panel>
    </flux:tab.group>
</flux:main>
