<?php

use App\Enums\Game\AccountLevel;
use App\Models\Game\Character;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new #[Layout('layouts.guest')] class extends Component {
    use WithPagination;

    #[Computed]
    public function characters()
    {
        return Character::query()
            ->with('guildMember', 'member')
            ->orderBy('ResetCount', 'desc')
            ->selectRaw('*, ROW_NUMBER() OVER (ORDER BY ResetCount DESC) as rank')
            ->paginate(10);
    }

    public function placeholder()
    {
        return <<<'HTML'
        <flux:table>
            <flux:columns>
                <flux:column>Name</flux:column>
                <flux:column>Class</flux:column>
                <flux:column>Level</flux:column>
                <flux:column>Resets</flux:column>
                <flux:column>Guild</flux:column>
                <flux:column>Location</flux:column>
            </flux:columns>

            <flux:rows>
                @for($i = 0; $i < 10; $i++)
                    <flux:row>
                        <flux:cell><div class="animate-pulse bg-zinc-200 dark:bg-zinc-800 h-4 rounded w-full"></div></flux:cell>
                        <flux:cell><div class="animate-pulse bg-zinc-200 dark:bg-zinc-800 h-4 rounded w-full"></div></flux:cell>
                        <flux:cell><div class="animate-pulse bg-zinc-200 dark:bg-zinc-800 h-4 rounded w-full"></div></flux:cell>
                        <flux:cell><div class="animate-pulse bg-zinc-200 dark:bg-zinc-800 h-4 rounded w-full"></div></flux:cell>
                        <flux:cell><div class="animate-pulse bg-zinc-200 dark:bg-zinc-800 h-4 rounded w-full"></div></flux:cell>
                        <flux:cell><div class="animate-pulse bg-zinc-200 dark:bg-zinc-800 h-4 rounded w-full"></div></flux:cell>
                    </flux:row>
                @endfor
            </flux:rows>
        </flux:table>
        HTML;
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
            <flux:column>Location</flux:column>
            <flux:column>
                <div class="flex items-center gap-2">
                    <span>Hunter Score</span>

                    <flux:modal.trigger name="edit-profile">
                        <flux:button icon="information-circle" size="sm" variant="ghost"/>
                    </flux:modal.trigger>

                    <flux:modal name="edit-profile" variant="flyout" position="bottom" class="space-y-6">
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

                    <flux:cell class="flex items-center space-x-2">
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
                        {{ $character->MapNumber->getLabel() }}
                    </flux:cell>

                    <flux:cell>

                        <div>
                            <flux:modal.trigger name="score-{{ $character->Name }}">
                                <flux:button size="sm" variant="ghost" icon-trailing="chevron-down">
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
                </flux:row>
            @endforeach
        </flux:rows>
    </flux:table>
</div>
