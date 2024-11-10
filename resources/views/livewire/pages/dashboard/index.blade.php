<?php

use App\Models\Game\Character;
use App\Models\User\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use App\Enums\Game\CharacterClass;

new #[Layout('layouts.app')] class extends Component {
    public $sortBy = 'ResetCount';
    public $sortDirection = 'desc';

    public function sort($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy        = $column;
            $this->sortDirection = 'asc';
        }
    }

    #[Computed]
    public function characters()
    {
        return Character::query()
            ->select('Name', 'cLevel', 'ResetCount', 'Class', 'PkCount')
            ->where('AccountID', auth()->user()->name)
            ->tap(fn($query) => $this->sortBy ? $query->orderBy($this->sortBy, $this->sortDirection) : $query)
            ->get();
    }
} ?>

<div class="space-y-6">
    <livewire:pages.dashboard.card/>

    <flux:table>
        <flux:columns>
            <flux:column>{{ __('Character') }}</flux:column>
            <flux:column>{{ __('Class') }}</flux:column>
            <flux:column>{{ __('Kills') }}</flux:column>
            <flux:column>{{ __('Level') }}</flux:column>
            <flux:column sortable :sorted="$sortBy === 'ResetCount'" :direction="$sortDirection"
                         wire:click="sort('ResetCount')">
                {{ __('Resets') }}
            </flux:column>
        </flux:columns>

        <flux:rows>
            @foreach ($this->characters as $character)
                <livewire:pages.dashboard.character-row :$character wire:key="{{ $character->Name }}"/>
            @endforeach
        </flux:rows>
    </flux:table>

    <flux:radio.group label="Choose Your Package" variant="cards" class="flex-col">
        <flux:radio value="standard" checked>
            <flux:radio.indicator/>

            <div class="flex-1">
                <flux:subheading size="sm">
                    Bronze Package
                </flux:subheading>
                <flux:heading class="leading-4">€ 20</flux:heading>
                <flux:subheading size="sm">500 tokens</flux:subheading>
            </div>
        </flux:radio>

        <flux:radio value="standard">
            <flux:radio.indicator/>

            <div class="flex-1">
                <flux:subheading size="sm" class="flex items-center">
                    <span>Silver Package</span>
                    <flux:spacer/>
                    <flux:badge size="sm" color="green" inset="top bottom">
                        Most popular
                    </flux:badge>
                </flux:subheading>
                <flux:heading class="leading-4">€ 40</flux:heading>
                <flux:subheading size="sm">500 tokens</flux:subheading>
            </div>
        </flux:radio>

        <flux:radio value="standard">
            <flux:radio.indicator/>

            <div class="flex-1">
                <flux:subheading size="sm">
                    Gold Package
                </flux:subheading>
                <flux:heading class="leading-4">€ 80</flux:heading>
                <flux:subheading size="sm">500 tokens</flux:subheading>
            </div>
        </flux:radio>

        <flux:radio value="standard">
            <flux:radio.indicator/>

            <div class="flex-1">
                <flux:subheading size="sm">
                    Platinum Package
                </flux:subheading>
                <flux:heading class="leading-4">€ 160</flux:heading>
                <flux:subheading size="sm">500 tokens</flux:subheading>
            </div>
        </flux:radio>

        <flux:radio value="standard">
            <flux:radio.indicator/>

            <div class="flex-1">
                <flux:subheading size="sm">
                    Diamond Package
                </flux:subheading>
                <flux:heading class="leading-4">€ 320</flux:heading>
                <flux:subheading size="sm">500 tokens</flux:subheading>
            </div>
        </flux:radio>
        <flux:radio value="standard" checked>
            <flux:radio.indicator/>

            <div class="flex-1">
                <flux:subheading size="sm">
                    Bronze Package
                </flux:subheading>
                <flux:heading class="leading-4">€ 20</flux:heading>
                <flux:subheading size="sm">500 tokens</flux:subheading>
            </div>
        </flux:radio>

        <flux:radio value="standard">
            <flux:radio.indicator/>

            <div class="flex-1">
                <flux:subheading size="sm" class="flex items-center">
                    <span>Silver Package</span>
                    <flux:spacer/>
                    <flux:badge size="sm" color="green" inset="top bottom">
                        Most popular
                    </flux:badge>
                </flux:subheading>
                <flux:heading class="leading-4">€ 40</flux:heading>
                <flux:subheading size="sm">500 tokens</flux:subheading>
            </div>
        </flux:radio>

        <flux:radio value="standard">
            <flux:radio.indicator/>

            <div class="flex-1">
                <flux:subheading size="sm">
                    Gold Package
                </flux:subheading>
                <flux:heading class="leading-4">€ 80</flux:heading>
                <flux:subheading size="sm">500 tokens</flux:subheading>
            </div>
        </flux:radio>

        <flux:radio value="standard">
            <flux:radio.indicator/>

            <div class="flex-1">
                <flux:subheading size="sm">
                    Platinum Package
                </flux:subheading>
                <flux:heading class="leading-4">€ 160</flux:heading>
                <flux:subheading size="sm">500 tokens</flux:subheading>
            </div>
        </flux:radio>

        <flux:radio value="standard">
            <flux:radio.indicator/>

            <div class="flex-1">
                <flux:subheading size="sm">
                    Diamond Package
                </flux:subheading>
                <flux:heading class="leading-4">€ 320</flux:heading>
                <flux:subheading size="sm">500 tokens</flux:subheading>
            </div>
        </flux:radio>
        <flux:radio value="standard" checked>
            <flux:radio.indicator/>

            <div class="flex-1">
                <flux:subheading size="sm">
                    Bronze Package
                </flux:subheading>
                <flux:heading class="leading-4">€ 20</flux:heading>
                <flux:subheading size="sm">500 tokens</flux:subheading>
            </div>
        </flux:radio>

        <flux:radio value="standard">
            <flux:radio.indicator/>

            <div class="flex-1">
                <flux:subheading size="sm" class="flex items-center">
                    <span>Silver Package</span>
                    <flux:spacer/>
                    <flux:badge size="sm" color="green" inset="top bottom">
                        Most popular
                    </flux:badge>
                </flux:subheading>
                <flux:heading class="leading-4">€ 40</flux:heading>
                <flux:subheading size="sm">500 tokens</flux:subheading>
            </div>
        </flux:radio>

        <flux:radio value="standard">
            <flux:radio.indicator/>

            <div class="flex-1">
                <flux:subheading size="sm">
                    Gold Package
                </flux:subheading>
                <flux:heading class="leading-4">€ 80</flux:heading>
                <flux:subheading size="sm">500 tokens</flux:subheading>
            </div>
        </flux:radio>

        <flux:radio value="standard">
            <flux:radio.indicator/>

            <div class="flex-1">
                <flux:subheading size="sm">
                    Platinum Package
                </flux:subheading>
                <flux:heading class="leading-4">€ 160</flux:heading>
                <flux:subheading size="sm">500 tokens</flux:subheading>
            </div>
        </flux:radio>

        <flux:radio value="standard">
            <flux:radio.indicator/>

            <div class="flex-1">
                <flux:subheading size="sm">
                    Diamond Package
                </flux:subheading>
                <flux:heading class="leading-4">€ 320</flux:heading>
                <flux:subheading size="sm">500 tokens</flux:subheading>
            </div>
        </flux:radio>
        <flux:radio value="standard" checked>
            <flux:radio.indicator/>

            <div class="flex-1">
                <flux:subheading size="sm">
                    Bronze Package
                </flux:subheading>
                <flux:heading class="leading-4">€ 20</flux:heading>
                <flux:subheading size="sm">500 tokens</flux:subheading>
            </div>
        </flux:radio>

        <flux:radio value="standard">
            <flux:radio.indicator/>

            <div class="flex-1">
                <flux:subheading size="sm" class="flex items-center">
                    <span>Silver Package</span>
                    <flux:spacer/>
                    <flux:badge size="sm" color="green" inset="top bottom">
                        Most popular
                    </flux:badge>
                </flux:subheading>
                <flux:heading class="leading-4">€ 40</flux:heading>
                <flux:subheading size="sm">500 tokens</flux:subheading>
            </div>
        </flux:radio>

        <flux:radio value="standard">
            <flux:radio.indicator/>

            <div class="flex-1">
                <flux:subheading size="sm">
                    Gold Package
                </flux:subheading>
                <flux:heading class="leading-4">€ 80</flux:heading>
                <flux:subheading size="sm">500 tokens</flux:subheading>
            </div>
        </flux:radio>

        <flux:radio value="standard">
            <flux:radio.indicator/>

            <div class="flex-1">
                <flux:subheading size="sm">
                    Platinum Package
                </flux:subheading>
                <flux:heading class="leading-4">€ 160</flux:heading>
                <flux:subheading size="sm">500 tokens</flux:subheading>
            </div>
        </flux:radio>

        <flux:radio value="standard">
            <flux:radio.indicator/>

            <div class="flex-1">
                <flux:subheading size="sm">
                    Diamond Package
                </flux:subheading>
                <flux:heading class="leading-4">€ 320</flux:heading>
                <flux:subheading size="sm">500 tokens</flux:subheading>
            </div>
        </flux:radio>
    </flux:radio.group>
</div>
