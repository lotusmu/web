<?php

use App\Actions\TransferZen;
use App\Enums\Utility\ResourceType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Enum;
use Livewire\Attributes\Computed;
use Livewire\Volt\Component;

new class extends Component {
    public $source = '';
    public $destination = '';
    public $sourceCharacter = '';
    public $destinationCharacter = '';
    public $amount = 0;

    public function rules(): array
    {
        return [
            'source'               => 'required|in:wallet,character',
            'destination'          => 'required|in:wallet,character',
            'sourceCharacter'      => 'required_if:source,character',
            'destinationCharacter' => 'required_if:destination,character',
            'amount'               => 'required|integer|min:1',
        ];
    }

    #[Computed]
    public function characters()
    {
        return Auth::user()->member->characters->map(function ($character) {
            return [
                'name' => $character->Name,
                'zen'  => $character->Money,
            ];
        });
    }

    #[Computed]
    public function walletZen()
    {
        return Auth::user()->getResourceValue(ResourceType::ZEN);
    }

    public function transfer(TransferZen $action): void
    {
        $this->validate();

        $user = Auth::user();

        $success = $action->handle(
            $user,
            $this->source,
            $this->destination,
            $this->sourceCharacter,
            $this->destinationCharacter,
            $this->amount
        );

        if ($success) {
            $this->reset(['source', 'destination', 'sourceCharacter', 'destinationCharacter', 'amount']);
        }
    }
}; ?>

<div x-data="{
        amount: $wire.entangle('amount'),
        source: $wire.entangle('source'),
        destination: $wire.entangle('destination'),
        sourceCharacter: $wire.entangle('sourceCharacter'),
        destinationCharacter: $wire.entangle('destinationCharacter'),
        updateDestination() {
            if (this.source === 'wallet') {
                this.destination = 'character';
            }
        }
    }"
     x-effect="updateDestination"
     class="space-y-6">
    <header>
        <flux:heading size="lg">
            {{ __('Transfer Zen') }}
        </flux:heading>

        <x-flux::subheading>
            {{ __('Move Zen seamlessly between your wallet and characters.') }}
        </x-flux::subheading>
    </header>

    <div class="flex max-sm:flex-col max-sm:space-y-6">
        <div class="space-y-6 flex-1">
            <flux:radio.group wire:model="source" label="{{ __('From (Source)') }}">
                <flux:radio value="wallet" label="{{ __('Zen Wallet') }} ({{ number_format($this->walletZen) }})"/>
                <flux:radio value="character" label="{{ __('Character') }}"/>
            </flux:radio.group>

            <div x-show="source === 'character'">
                <flux:select wire:model="sourceCharacter" variant="listbox"
                             placeholder="{{ __('Select source character') }}">
                    @foreach($this->characters as $character)
                        <flux:option value="{{ $character['name'] }}">{{ $character['name'] }}
                            ({{ number_format($character['zen']) }})
                        </flux:option>
                    @endforeach
                </flux:select>
            </div>
        </div>

        <div class="mt-8 shrink-0 w-32 max-sm:hidden">
            <flux:icon.chevron-double-right/>
        </div>

        <flux:separator class="sm:hidden"/>

        <div class="space-y-6 flex-1">
            <flux:radio.group wire:model="destination" label="{{ __('To (Destination)') }}">
                <flux:radio value="wallet" label="{{ __('Zen Wallet') }} ({{ number_format($this->walletZen) }})"
                            x-bind:disabled="source === 'wallet'"/>
                <flux:radio value="character" label="{{ __('Character') }}"/>
            </flux:radio.group>

            <div x-show="destination === 'character'">
                <flux:select wire:model="destinationCharacter" variant="listbox"
                             placeholder="{{ __('Select destination character') }}">
                    @foreach($this->characters as $character)
                        <flux:option value="{{ $character['name'] }}"
                                     x-bind:disabled="source === 'character' && sourceCharacter === '{{ $character['name'] }}'">
                            {{ $character['name'] }} ({{ number_format($character['zen']) }})
                        </flux:option>
                    @endforeach
                </flux:select>
            </div>
        </div>
    </div>

    <flux:input
        wire:model="amount"
        x-model.number="amount"
        type="number"
        label="{{ __('Amount') }}"
        min="0"
        step="1"
    />

    <flux:button wire:click="transfer" type="button" variant="primary">
        {{ __('Transfer') }}
    </flux:button>
</div>
