<flux:row>
    <flux:cell>
        <flux:link variant="ghost"
                   :href="route('character', ['name' => $this->character->Name])"
                   wire:navigate>
            {{ $this->character->Name }}
        </flux:link>
    </flux:cell>

    <flux:cell>
        <div class="flex items-center gap-3">
            <flux:avatar size="xs" src="{{ asset($this->character->Class->getImagePath()) }}"/>

            <span class="max-sm:hidden">
                {{  $this->character->Class->getLabel()  }}
            </span>
        </div>
    </flux:cell>

    <flux:cell>
        <x-guild-identity :guildMember="$this->character->guildMember"/>
    </flux:cell>

    <flux:cell>{{ $this->character->PkCount }}</flux:cell>

    <flux:cell>{{ $this->character->cLevel }}</flux:cell>

    <flux:cell>{{ $this->character->ResetCount }}</flux:cell>

    <flux:cell align="end">
        <flux:dropdown align="end">
            <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal"/>

            <flux:menu variant="solid">
                <flux:menu.item icon="information-circle"
                                href="https://wiki.yulanmu.com/gameplay-systems/reset-system#how-to-reset"
                                target="_blank">
                    {{ __('How to Reset') }}
                </flux:menu.item>

                @if($this->canSkipQuest)
                    <flux:modal.trigger name="skip_quest_{{ $this->character->Name }}">
                        <flux:menu.item icon="forward">
                            {{ __('Skip Quest') }}
                        </flux:menu.item>
                    </flux:modal.trigger>
                @else
                    <div {{ $this->shouldPoll ? 'wire:poll.5s=pollQuestStatus' : '' }}></div>
                @endif

                @if($this->canClearPk)
                    <flux:modal.trigger name="pk_clear_{{ $this->character->Name }}">
                        <flux:menu.item icon="arrow-path">
                            {{ __('PK Clear') }}
                        </flux:menu.item>
                    </flux:modal.trigger>
                @endif

                <flux:menu.item icon="arrows-pointing-out" wire:click="unstuck">
                    {{ __('Unstuck Character') }}
                </flux:menu.item>
            </flux:menu>
        </flux:dropdown>

        @if($this->canClearPk)
            <flux:modal name="pk_clear_{{ $this->character->Name }}" class="md:w-96 space-y-6 text-start">
                <div>
                    <flux:heading size="lg">{{ __('Clear Player Kills ? ') }}</flux:heading>
                    <flux:subheading>
                        {!! __('Are you sure you want to clear all player kills for <strong>:name</strong>?', [
                           'name' => $this->character->Name
                       ]) !!}
                    </flux:subheading>
                </div>

                <div>
                    <flux:text class="flex gap-1">
                        {{ __('Kills:') }}
                        <flux:heading>{{ $this->character->PkCount }}</flux:heading>
                    </flux:text>
                    <flux:text class="flex gap-1">
                        {{ __('Cost:') }}
                        <flux:heading>{{ number_format($this->pkClearCost) }} {{ __($this->pkClearResource) }}</flux:heading>
                    </flux:text>
                </div>

                <div class="flex gap-2">
                    <flux:spacer/>

                    <flux:modal.close>
                        <flux:button variant="ghost">{{ __('Cancel') }}</flux:button>
                    </flux:modal.close>

                    <form wire:submit="clearKills">
                        <flux:button type="submit"
                                     variant="primary">{{ __('Confirm') }}</flux:button>
                    </form>
                </div>
            </flux:modal>
        @endif

        @if($this->canSkipQuest)
            <flux:modal name="skip_quest_{{ $this->character->Name }}" class="md:w-96 space-y-6 text-start">
                <div>
                    <flux:heading size="lg">{{ __('Skip Quest?') }}</flux:heading>
                    <flux:subheading>
                        {!! __('Are you sure you want to skip the quest for <strong>:name</strong>?', [
                           'name' => $this->character->Name
                       ]) !!}
                    </flux:subheading>
                </div>

                <div>
                    <flux:text class="flex gap-1">
                        {{ __('Quest Number:') }}
                        <flux:heading>{{ ($this->character->quest?->Quest ?? 0) + 1 }}</flux:heading>
                    </flux:text>
                    <flux:text class="flex gap-1">
                        {{ __('Cost:') }}
                        <flux:heading>{{ number_format($this->questSkipCost) }} {{ __($this->questSkipResource) }}</flux:heading>
                    </flux:text>
                </div>

                <div class="flex items-center gap-1">
                    <flux:icon.information-circle variant="mini" inset="top bottom"/>
                    <flux:text>
                        {{ __('Quest reward remains available in-game.') }}
                    </flux:text>
                </div>

                <div class="flex gap-2">
                    <flux:spacer/>

                    <flux:modal.close>
                        <flux:button variant="ghost">{{ __('Cancel') }}</flux:button>
                    </flux:modal.close>

                    <form wire:submit="skipQuest">
                        <flux:button type="submit"
                                     variant="primary">{{ __('Confirm') }}</flux:button>
                    </form>
                </div>
            </flux:modal>
        @endif
    </flux:cell>
</flux:row>
