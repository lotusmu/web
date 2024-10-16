<?php

use App\Actions\SendResources;
use App\Enums\Utility\OperationType;
use App\Enums\Utility\ResourceType;
use App\Models\Concerns\Taxable;
use App\Models\Game\Character;
use App\Models\User\Member;
use App\Models\User\User;
use App\Models\Utility\Tax;
use Illuminate\Validation\Rules\Enum;
use Livewire\Attributes\Computed;
use Livewire\Volt\Component;

new class extends Component {
    use Taxable;

    public $sender;
    public string $recipient = '';
    public ?ResourceType $resourceType = null;
    public int $amount = 0;

    public function mount(): void
    {
        $this->sender        = Auth::user()->id;
        $this->operationType = OperationType::TRANSFER;
    }

    public function rules(): array
    {
        return [
            'recipient'    => 'required|string|min:4|max:10',
            'resourceType' => ['required', new Enum(ResourceType::class)],
            'amount'       => 'required|integer|min:100',
        ];
    }

    public function getRecipientUserProperty(): ?User
    {
        return Character::findUserByCharacterName($this->recipient);
    }

    public function transfer(SendResources $action): void
    {
        $this->validate();

        if ( ! $this->recipientUser) {
            $this->addError('recipient', 'Character not found or no associated user account.');

            return;
        }

        $sender = User::findOrFail($this->sender);

        $taxAmount = $this->calculateTax($this->amount);

        $success = $action->handle(
            $sender,
            $this->recipientUser,
            $this->resourceType,
            $this->amount,
            $taxAmount
        );

        if ($success) {
            $this->reset(['recipient', 'resourceType', 'amount']);
            $this->dispatch('resourcesUpdated');
        }
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
            @foreach(ResourceType::cases() as $type)
                <flux:option value="{{ $type->value }}">{{ __($type->getLabel()) }}</flux:option>
            @endforeach
        </flux:select>

        <div x-data="{
                amount: $wire.entangle('amount'),
                taxRate: {{ $this->taxRate }},
                get totalWithTax() {
                    if (this.amount <= 0) return 0;
                    const taxAmount = Math.round(this.amount * (this.taxRate / 100));
                    return this.amount + taxAmount;
                }
            }" class="grid sm:grid-cols-2 items-start gap-4">
            <flux:input
                clearable
                label="{{ __('Amount') }}"
                wire:model="amount"
                x-model.number="amount"
                type="number"
                min="0"
                step="1"
            />
            <flux:input
                label="{{ __('Total (including ' . $this->taxRate . '% tax)') }}"
                x-bind:value="new Intl.NumberFormat().format(totalWithTax)"
                type="text"
                disabled
            />
        </div>

        <flux:input
            clearable
            wire:model="recipient"
            label="{{ __('Recipient') }}"
            placeholder="{{ __('Enter character name') }}"
        />

        <flux:button type="submit" variant="primary">
            {{ __('Send') }}
        </flux:button>
    </form>
</div>
