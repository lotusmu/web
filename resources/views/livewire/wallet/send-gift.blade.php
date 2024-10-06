<?php

use App\Actions\TransferResources;
use App\Models\Game\Character;
use App\Models\User\Member;
use App\Models\User\User;
use App\Services\ResourceTypeValidator;
use Livewire\Attributes\Rule;
use Livewire\Volt\Component;

new class extends Component {
    public $sender;
    public $recipient = '';
    public string $resourceType = '';
    public int $amount;


    public function mount(): void
    {
        $this->sender = Auth::user()->id;
    }

    public function rules(): array
    {
        return [
            'recipient'    => 'required|string|min:4|max:10',
            'resourceType' => 'required|in:tokens,credits,zen',
            'amount'       => 'required|integer|min:1',
        ];
    }

    public function getRecipientUserProperty()
    {
        $character = Character::where('Name', $this->recipient)->first();

        if ( ! $character) {
            return null;
        }

        $member = Member::where('memb___id', $character->AccountID)->first();

        if ( ! $member) {
            return null;
        }

        return User::where('name', $member->memb___id)->first();
    }

    public function transfer(TransferResources $action): void
    {
        $this->validate();

        $recipientUser = $this->recipientUser;

        if ( ! $recipientUser) {
            $this->addError('recipient', 'Character not found or no associated user account.');

            return;
        }

        $sender = User::findOrFail($this->sender);

        $action->handle($sender, $recipientUser, $this->resourceType, $this->amount);

        $this->reset(['recipient', 'resourceType', 'amount']);
    }
}; ?>

<div>
    <header>
        <flux:heading size="lg">
            {{ __('Send Gift') }}
        </flux:heading>

        <x-flux::subheading>
            {{ __('Transfer tokens, credits, or zen to another user.') }}
        </x-flux::subheading>
    </header>


    <form wire:submit="transfer" class="mt-6 space-y-6">
        <flux:select wire:model="resourceType" variant="listbox" placeholder="{{__('Choose currency type...')}}">
            <flux:option value="tokens">{{__('Tokens')}}</flux:option>
            <flux:option value="credits">{{__('Credits')}}</flux:option>
            <flux:option value="zen">{{__('Zen')}}</flux:option>
        </flux:select>

        <div x-data="{
                amount: 0,
                get totalWithTax() {
                    return this.amount > 0 ? Math.ceil(this.amount * 1.05) : 0;
                }
            }"
             class="grid sm:grid-cols-2 items-start gap-4">
            <flux:input
                wire:model="amount"
                type="number"
                label="{{ __('Amount') }}"
                @input="amount = parseInt($event.target.value) || 0"
                min="0"
                step="1"
            />
            <flux:input
                type="number"
                label="{{ __('Total (including 5% tax)') }}"
                x-bind:value="totalWithTax"
                disabled
            />
        </div>

        <flux:input
            wire:model="recipient"
            label="{{ __('Recipient') }}"
            placeholder="{{ __('Enter character name') }}"
        />

        <flux:button type="submit" variant="primary">
            {{ __('Send') }}
        </flux:button>
    </form>
</div>
