<?php

use App\Models\Game\Character;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use App\Enums\Game\AccountLevel;
use Livewire\Attributes\Modelable;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new #[Layout('layouts.guest')] class extends Component {
    use WithPagination;

    #[Modelable]
    public string $class = 'all';

    #[Computed]
    public function characters()
    {
        $query = Character::query()
            ->with('guildMember', 'member');

        if ($this->class !== 'all') {
            $query->where('Class', $this->class);
        }

        return $query->orderBy('ResetCount', 'desc')
            ->selectRaw('*, ROW_NUMBER() OVER (ORDER BY ResetCount DESC) as rank')
            ->paginate(15);
    }
} ?>

<div class="overflow-x-auto">
    <flux:table :paginate="$this->characters" wire:loading.class="opacity-50">
        <flux:columns>
            <flux:column>Name</flux:column>
            <flux:column>Class</flux:column>
            <flux:column>Level</flux:column>
            <flux:column>Resets</flux:column>
            <flux:column>Guild</flux:column>
            <flux:column>
                <div class="flex items-center gap-2">
                    <span>Event Score</span>

                    <flux:modal.trigger name="score-info">
                        <flux:button icon="information-circle" size="sm" inset="top bottom" variant="ghost"/>
                    </flux:modal.trigger>

                    <flux:modal name="score-info" variant="flyout" position="bottom" class="space-y-6">
                        <div>
                            <flux:heading size="lg">Monster Score Table</flux:heading>
                            <flux:subheading>
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit. At,
                                consequuntur.
                            </flux:subheading>
                        </div>

                        <div>
                            <flux:text>
                                Lorem ipsum dolor sit amet.
                            </flux:text>
                            <flux:text>
                                Lorem ipsum dolor sit amet.
                            </flux:text>
                            <flux:text>
                                Lorem ipsum dolor sit amet.
                            </flux:text>
                            <flux:text>
                                Lorem ipsum dolor sit amet.
                            </flux:text>
                            <flux:text>
                                Lorem ipsum dolor sit amet.
                            </flux:text>
                            <flux:text>
                                Lorem ipsum dolor sit amet.
                            </flux:text>
                            <flux:text>
                                Lorem ipsum dolor sit amet.
                            </flux:text>
                            <flux:text>
                                Lorem ipsum dolor sit amet.
                            </flux:text>
                            <flux:text>
                                Lorem ipsum dolor sit amet.
                            </flux:text>
                            <flux:text>
                                Lorem ipsum dolor sit amet.
                            </flux:text>
                        </div>
                    </flux:modal>
                </div>
            </flux:column>

            <flux:column>
                <div class="flex items-center gap-2">
                    <span>Event Score All Time</span>

                    <flux:modal.trigger name="score-info-all-time">
                        <flux:button icon="information-circle" size="sm" inset="top bottom" variant="ghost"/>
                    </flux:modal.trigger>

                    <flux:modal name="score-info-all-time" variant="flyout" position="bottom" class="space-y-6">
                        <div>
                            <flux:heading size="lg">Monster Score Table</flux:heading>
                            <flux:subheading>
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit. At,
                                consequuntur.
                            </flux:subheading>
                        </div>

                        <div>
                            <flux:text>
                                Lorem ipsum dolor sit amet.
                            </flux:text>
                            <flux:text>
                                Lorem ipsum dolor sit amet.
                            </flux:text>
                            <flux:text>
                                Lorem ipsum dolor sit amet.
                            </flux:text>
                            <flux:text>
                                Lorem ipsum dolor sit amet.
                            </flux:text>
                            <flux:text>
                                Lorem ipsum dolor sit amet.
                            </flux:text>
                            <flux:text>
                                Lorem ipsum dolor sit amet.
                            </flux:text>
                            <flux:text>
                                Lorem ipsum dolor sit amet.
                            </flux:text>
                            <flux:text>
                                Lorem ipsum dolor sit amet.
                            </flux:text>
                            <flux:text>
                                Lorem ipsum dolor sit amet.
                            </flux:text>
                            <flux:text>
                                Lorem ipsum dolor sit amet.
                            </flux:text>
                        </div>
                    </flux:modal>
                </div>
            </flux:column>
        </flux:columns>

        <flux:rows>
            @foreach($this->characters as $character)
                <flux:row wire:key="{{ $character->Name }}">

                    <flux:cell class="flex items-center space-x-2 h-full">
                            <span>
                                {{ $character->rank }}.
                            </span>

                        <flux:link variant="ghost" href="#">
                            {{ $character->Name }}
                        </flux:link>

                        @if($character->member->AccountLevel !== AccountLevel::Regular)
                            <flux:icon.fire variant="mini"
                                            class="text-{{ $character->member->AccountLevel->badgeColor() }}-500"/>
                        @endif
                    </flux:cell>

                    <flux:cell>
                        <div class="flex items-center gap-3">
                            <flux:avatar size="xs"
                                         src="{{ asset($character->Class->getImagePath()) }}"/>

                            <span class="max-sm:hidden">
                                {{ $character->Class->getLabel()  }}
                            </span>
                        </div>
                    </flux:cell>

                    <flux:cell>
                        {{ $character->cLevel }}
                    </flux:cell>

                    <flux:cell>
                        {{ $character->ResetCount }}
                    </flux:cell>

                    <flux:cell>
                        <x-guild-identity :guildMember="$character->guildMember"/>
                    </flux:cell>

                    <flux:cell>
                        <div>
                            <flux:modal.trigger name="score-{{ $character->Name }}">
                                <flux:button size="sm" variant="ghost" inset="top bottom" icon-trailing="chevron-down">
                                    <span>{{ rand(400,900) }}</span>
                                </flux:button>
                            </flux:modal.trigger>

                            <flux:modal name="score-{{ $character->Name }}" variant="flyout" position="bottom"
                                        class="space-y-6">
                                <div>
                                    <flux:heading size="lg">{{ $character->Name }} Hunt Score</flux:heading>
                                    <flux:subheading>
                                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. At,
                                        consequuntur.
                                    </flux:subheading>
                                </div>

                                <div>
                                    <flux:text>
                                        Lorem ipsum dolor sit amet.
                                    </flux:text>
                                    <flux:text>
                                        Lorem ipsum dolor sit amet.
                                    </flux:text>
                                </div>
                            </flux:modal>
                        </div>
                    </flux:cell>

                    <flux:cell>
                        <div>
                            <flux:modal.trigger name="score-total-{{ $character->Name }}">
                                <flux:button size="sm" variant="ghost" inset="top bottom" icon-trailing="chevron-down">
                                    <span>{{ rand(4000,9000) }}</span>
                                </flux:button>
                            </flux:modal.trigger>

                            <flux:modal name="score-total-{{ $character->Name }}" variant="flyout" position="bottom"
                                        class="space-y-6">
                                <div>
                                    <flux:heading size="lg">{{ $character->Name }} Hunt Score</flux:heading>
                                    <flux:subheading>
                                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. At,
                                        consequuntur.
                                    </flux:subheading>
                                </div>

                                <div>
                                    <flux:text>
                                        Lorem ipsum dolor sit amet.
                                    </flux:text>
                                    <flux:text>
                                        Lorem ipsum dolor sit amet.
                                    </flux:text>
                                </div>
                            </flux:modal>
                        </div>
                    </flux:cell>
                </flux:row>
            @endforeach
        </flux:rows>
    </flux:table>
</div>
