<?php

use App\Actions\Character\ClearKills;
use App\Actions\Character\SkipQuest;
use App\Enums\Game\Map;
use App\Enums\Utility\OperationType;
use App\Enums\Utility\ResourceType;
use App\Models\Concerns\Taxable;
use App\Models\Game\Character;
use App\Models\User\User;
use App\Models\Utility\Setting;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {
    use Taxable;

    public Character $character;

    public User $user;

    public function mount(Character $character): void
    {
        $this->character     = $character;
        $this->user          = auth()->user();
        $this->operationType = OperationType::PK_CLEAR;
        $this->initializeTaxable();
    }

    #[Computed]
    public function pkClearCost(): int
    {
        return $this->calculateRate($this->character->PkCount);
    }

    #[Computed]
    public function pkClearResource(): string
    {
        return ResourceType::from($this->getResourceType())->getLabel();
    }

    #[Computed]
    public function canClearPk(): bool
    {
        $settings    = Setting::getGroup(OperationType::PK_CLEAR->value);
        $hasSettings = isset($settings['pk_clear']['cost']) && isset($settings['pk_clear']['resource']);

        return $hasSettings && $this->character->PkCount > 0;
    }

    #[Computed]
    public function questSkipCost(): int
    {
        return Setting::getValue(OperationType::QUEST_SKIP->value, 'quest_skip.cost', 0);
    }

    #[Computed]
    public function questSkipResource(): string
    {
        $resourceType = Setting::getValue(OperationType::QUEST_SKIP->value, 'quest_skip.resource', 'tokens');

        return ResourceType::from($resourceType)->getLabel();
    }

    #[Computed]
    public function canSkipQuest(): bool
    {
        return SkipQuest::isAvailable() && $this->character->hasActiveQuest();
    }

    public function unstuck(): void
    {
        if ($this->user->isOnline()) {
            return;
        }

        $this->character->MapNumber = Map::Lorencia;
        $this->character->MapPosX   = 125;
        $this->character->MapPosY   = 125;

        $this->character->save();

        Flux::toast(
            text: __('Character moved successfully to Lorencia'),
            heading: __('Success'),
            variant: 'success'
        );
    }

    public function clearKills(ClearKills $action): void
    {
        $action->handle($this->user, $this->character);

        $this->modal('pk_clear_'.$this->character->Name)->close();
    }

    public function skipQuest(SkipQuest $action): void
    {
        $action->handle($this->user, $this->character);

        $this->modal('skip_quest_'.$this->character->Name)->close();
    }
};

?>

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
                                href="https://wiki.lotusmu.org/gameplay-systems/reset-system#how-to-reset"
                                target="_blank">
                    {{ __('How to Reset') }}
                </flux:menu.item>

                @if($this->canSkipQuest)
                    <flux:modal.trigger name="skip_quest_{{ $this->character->Name }}">
                        <flux:menu.item icon="forward">
                            {{ __('Skip Quest') }}
                        </flux:menu.item>
                    </flux:modal.trigger>
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
