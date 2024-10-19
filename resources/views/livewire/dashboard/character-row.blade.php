<?php

use App\Actions\ClearKills;
use App\Enums\Game\Map;
use App\Enums\Ticket\TicketStatus;
use App\Enums\Utility\OperationType;
use App\Models\Concerns\Taxable;
use App\Models\Game\Character;
use App\Models\Ticket\Ticket;
use App\Models\User\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
        $this->user          = Character::findUserByCharacterName($this->character->Name);
        $this->operationType = OperationType::PK_CLEAR;
        $this->initializeTaxable();
    }

    #[Computed]
    public function clearCost(): int
    {
        return $this->calculateTax($this->character->PkCount);
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
            variant: 'success',
            heading: 'Success',
            text: 'Character moved successfully to Lorencia'
        );
    }


    public function clearKills(ClearKills $action): void
    {
        $action->handle($this->user, $this->character, $this->clearCost);

        $this->modal('pk-clear-'.$this->character->Name)->close();
    }
}

?>

<flux:row>
    <flux:cell>{{ $this->character->Name }}</flux:cell>
    <flux:cell class="flex items-center gap-3">
        <flux:avatar size="xs" src="{{ asset($this->character->Class->getImagePath()) }}"/>

        <span class="max-sm:hidden">
            {{  $this->character->Class->getLabel()  }}
        </span>
    </flux:cell>
    <flux:cell>{{ $this->character->PkCount }}</flux:cell>
    <flux:cell>{{ $this->character->cLevel }}</flux:cell>
    <flux:cell>{{ $this->character->ResetCount }}</flux:cell>
    <flux:cell align="end">
        <flux:dropdown align="end">
            <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal"/>

            <flux:menu variant="solid">
                <flux:modal.trigger name="pk-clear-{{ $this->character->Name }}">
                    <flux:menu.item icon="arrow-path">
                        {{ __('PK Clear') }}
                    </flux:menu.item>
                </flux:modal.trigger>


                <flux:menu.item icon="arrows-pointing-out" wire:click="unstuck">
                    {{ __('Unstuck Character') }}
                </flux:menu.item>
            </flux:menu>
        </flux:dropdown>

        <flux:modal name="pk-clear-{{ $this->character->Name }}" class="md:w-96 space-y-6 text-start">
            <div>
                <flux:heading size="lg">Clear Player Kills?</flux:heading>
                <flux:subheading>Are you sure you want to clear all player kills for
                    <strong>{{ $this->character->Name }}</strong>?
                </flux:subheading>
            </div>

            <div>
                <flux:text class="flex gap-1">
                    Kills:
                    <flux:heading>{{ $this->character->PkCount }}</flux:heading>
                </flux:text>
                <flux:text class="flex gap-1">
                    Cost:
                    <flux:heading>{{ number_format($this->clearCost) }} Zen</flux:heading>
                </flux:text>
            </div>

            <div class="flex gap-2">
                <flux:spacer/>

                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>

                <flux:button wire:click="clearKills" type="submit" variant="primary">Confirm</flux:button>
            </div>
        </flux:modal>
    </flux:cell>
</flux:row>


