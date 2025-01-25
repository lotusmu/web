<?php

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
            ->with('guildMember')
            ->orderBy('ResetCount', 'desc')
            ->selectRaw('*, ROW_NUMBER() OVER (ORDER BY ResetCount DESC) as rank')
            ->paginate(10);
    }
} ?>

<flux:main container>
    <x-page-header
        title="Who's on top?"
        kicker="Rankings"
        description="The numbers don’t lie — players and guilds ranked by their achievements."
    />

    <div class="flex items-center gap-8 whitespace-nowrap overflow-auto touch-pan-x no-scrollbar snap-x w-full">
        <!-- Dark Knight -->
        <div
            class="bg-gradient-to-t from-zinc-950/10 dark:from-white/10 to-transparent to-90% rounded-xl snap-center inline-flex items-center justify-center min-w-48 w-full">
            <div class="p-6 space-y-6">
                <div>
                    <div
                        class="w-28 h-28 mx-auto rounded-tr-full rounded-tl-full p-1  bg-gradient-to-b from-red-700 via-red-600 dark:from-red-600 dark:via-red-700 to-transparent to-85%">
                        <img src="{{ asset('images/characters/dk.png') }}" alt="Blade Knight"
                             class="w-full h-full rounded-xl object-cover"/>
                    </div>
                </div>

                <div class="flex flex-col text-center space-y-3">
                    <flux:link variant="subtle" href="#">
                        HEROIN
                    </flux:link>
                    <div
                        class="px-4 py-1.5 rounded-full bg-transparent dark:bg-black/30 inline-flex items-center gap-2 border border-red-600/90 dark:border-red-400/30">
                        <div class="text-red-600 dark:text-red-400 text-sm mx-auto">
                            Dark Knight
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dark Wizard -->
        <div
            class="bg-gradient-to-t from-zinc-950/10 dark:from-white/10 to-transparent to-90% rounded-xl snap-center inline-flex items-center justify-center min-w-48 w-full">
            <div class="p-6 space-y-6">
                <div>
                    <div
                        class="w-28 h-28 mx-auto rounded-tr-full rounded-tl-full p-1  bg-gradient-to-b from-blue-700 via-blue-600 dark:from-blue-600 dark:via-blue-700 to-transparent to-85%">
                        <img src="{{ asset('images/characters/dw.png') }}" alt="Dark Wizard"
                             class="w-full h-full rounded-xl object-cover"/>
                    </div>
                </div>

                <div class="flex flex-col text-center space-y-3">
                    <flux:link variant="subtle" href="#">
                        034
                    </flux:link>
                    <div
                        class="px-4 py-1.5 rounded-full bg-transparent dark:bg-black/30 inline-flex items-center gap-2 border border-blue-600/90 dark:border-blue-400/30">
                        <p class="text-blue-600 dark:text-blue-400 text-sm mx-auto">
                            Dark Wizard
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Muse Elf -->
        <div
            class="bg-gradient-to-t from-zinc-950/10 dark:from-white/10 to-transparent to-90% rounded-xl snap-center inline-flex items-center justify-center min-w-48 w-full">
            <div class="p-6 space-y-6">
                <div>
                    <div
                        class="w-28 h-28 mx-auto rounded-tr-full rounded-tl-full p-1  bg-gradient-to-b from-green-700 via-green-600 dark:from-green-600 dark:via-green-700 to-transparent to-85%">
                        <img src="{{ asset('images/characters/fe.png') }}" alt="Muse Elf"
                             class="w-full h-full rounded-xl object-cover"/>
                    </div>
                </div>

                <div class="flex flex-col text-center space-y-3">
                    <flux:link variant="subtle" href="#">
                        WeedQueen
                    </flux:link>
                    <div
                        class="px-4 py-1.5 rounded-full bg-transparent dark:bg-black/30 inline-flex items-center gap-2 border border-green-600/90 dark:border-green-400/30">
                        <div class="text-green-600 dark:text-green-400 text-sm mx-auto">
                            Muse Elf
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Magic Gladiator-->
        <div
            class="bg-gradient-to-t from-zinc-950/10 dark:from-white/10 to-transparent to-90% rounded-xl snap-center inline-flex items-center justify-center min-w-48 w-full">
            <div class="p-6 space-y-6">
                <div>
                    <div
                        class="w-28 h-28 mx-auto rounded-tr-full rounded-tl-full p-1  bg-gradient-to-b from-purple-700 via-purple-600 dark:from-purple-600 dark:via-purple-700 to-transparent to-85%">
                        <img src="{{ asset('images/characters/mg.png') }}" alt="Dark Lord"
                             class="w-full h-full rounded-xl object-cover"/>
                    </div>
                </div>

                <div class="flex flex-col text-center space-y-3">
                    <flux:link variant="subtle" href="#">
                        Peyote
                    </flux:link>
                    <div
                        class="px-4 py-1.5 rounded-full bg-transparent dark:bg-black/30 inline-flex items-center gap-2 border border-purple-600/90 dark:border-purple-400/30">
                        <div class="text-purple-600 dark:text-purple-400 text-sm mx-auto">
                            Magic Gladiator
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dark Lord -->
        <div
            class="bg-gradient-to-t from-zinc-950/10 dark:from-white/10 to-transparent to-90% rounded-xl snap-center inline-flex items-center justify-center min-w-48 w-full">
            <div class="p-6 space-y-6">
                <div>
                    <div
                        class="w-28 h-28 mx-auto rounded-tr-full rounded-tl-full p-1  bg-gradient-to-b from-yellow-700 via-yellow-600 dark:from-yellow-600 dark:via-yellow-700 to-transparent to-85%">
                        <img src="{{ asset('images/characters/dl.png') }}" alt="Soul Master"
                             class="w-full h-full rounded-xl object-cover"/>
                    </div>
                </div>

                <div class="flex flex-col text-center space-y-3">
                    <flux:link variant="subtle" href="#">
                        Conqueror
                    </flux:link>
                    <div
                        class="px-4 py-1.5 rounded-full bg-transparent dark:bg-black/30 inline-flex items-center gap-2 border border-yellow-600/90 dark:border-yellow-400/30">
                        <div class="text-yellow-600 dark:text-yellow-400 text-sm mx-auto">
                            Dark Lord
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex justify-end items-center my-8">
        <flux:button.group>
            <flux:button>Players</flux:button>
            <flux:button>Guilds</flux:button>
        </flux:button.group>
    </div>

    <div class="overflow-x-auto">
        <flux:table :paginate="$this->characters" wire:loading.class="opacity-50">
            <flux:columns>
                <flux:column>Name</flux:column>
                <flux:column>Class</flux:column>
                <flux:column>Level</flux:column>
                <flux:column>Resets</flux:column>
                <flux:column>Guild</flux:column>
                <flux:column>Location</flux:column>
            </flux:columns>

            <flux:rows>
                @foreach($this->characters as $character)
                    <flux:row wire:key="{{ $character->Name }}">

                        <flux:cell class="space-x-2">
                                    <span>
                                        {{ $character->rank }}.
                                    </span>

                            <flux:link variant="ghost" href="#">
                                {{ $character->Name }}
                            </flux:link>
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
                    </flux:row>
                @endforeach
            </flux:rows>
        </flux:table>
    </div>
</flux:main>
