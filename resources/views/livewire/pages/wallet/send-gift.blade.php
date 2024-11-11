<?php

use App\Actions\Wallet\SendResources;
use App\Enums\Utility\OperationType;
use App\Enums\Utility\ResourceType;
use App\Models\Concerns\Taxable;
use App\Models\Game\Character;
use App\Models\User\User;
use Illuminate\Validation\Rules\Enum;
use Livewire\Attributes\Computed;
use Livewire\Volt\Component;

new class extends Component {
    use Taxable;

    private const MIN_CHARS_TO_SEARCH = 3;
    private const MAX_SEARCH_RESULTS = 10;
    private const SEARCH_RATE_LIMIT = 20;
    private const RATE_LIMIT_DURATION = 60; // seconds

    public $sender;
    public string $recipient = '';
    public ?ResourceType $resourceType = null;
    public int $amount;
    public array $suggestions = [];

    public function mount(): void
    {
        $this->sender        = Auth::user()->id;
        $this->operationType = OperationType::TRANSFER;
        $this->initializeTaxable();
    }

    public function rules(): array
    {
        return [
            'recipient'    => 'required|string|min:4|max:10',
            'resourceType' => ['required', new Enum(ResourceType::class)],
            'amount'       => 'required|integer|min:100',
        ];
    }

    public function transfer(SendResources $action): void
    {
        $this->validate();

        $recipientUser = Character::findUserByCharacterName($this->recipient);

        if ( ! $recipientUser) {
            $this->addError('recipient', 'Character not found or no associated user account.');

            return;
        }

        $sender = User::findOrFail($this->sender);

        $success = $action->handle(
            $sender,
            $recipientUser,
            $this->resourceType,
            $this->amount
        );

        if ($success) {
            $this->reset(['recipient', 'resourceType', 'amount']);
            $this->dispatch('resourcesUpdated');
        }
    }

    public function updatedRecipient(): void
    {
        if (strlen($this->recipient) < self::MIN_CHARS_TO_SEARCH) {
            $this->suggestions = [];

            return;
        }

        $rateLimitKey = 'character_search_'.auth()->id();
        if (cache()->get($rateLimitKey, 0) > self::SEARCH_RATE_LIMIT) {
            return;
        }
        cache()->add($rateLimitKey, 0, now()->addSeconds(self::RATE_LIMIT_DURATION));
        cache()->increment($rateLimitKey);

        $searchTerm = substr(trim($this->recipient), 0, 10);

        $this->suggestions = cache()->remember(
            'characters:search:'.$searchTerm,
            300,
            fn() => Character::query()
                ->select('name')
                ->where('name', 'like', $searchTerm.'%')
                ->orderBy('name')
                ->limit(self::MAX_SEARCH_RESULTS)
                ->pluck('name')
                ->toArray()
        );
    }
} ?>

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
        <flux:select wire:model="resourceType" variant="listbox" placeholder="{{__('Choose resource type...')}}">
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
                label="{{ __('Total (including :rate% tax)', ['rate' => $this->taxRate]) }}"
                x-bind:value="new Intl.NumberFormat().format(totalWithTax)"
                type="text"
                disabled
            />
        </div>

        <flux:autocomplete
            wire:model.live.debounce.300ms="recipient"
            label="{{ __('Recipient') }}"
            placeholder="{{ __('Enter character name') }}"
            :filter="false"
        >
            @foreach($suggestions as $name)
                <flux:autocomplete.item wire:key="{{ $name }}">{{ $name }}</flux:autocomplete.item>
            @endforeach
        </flux:autocomplete>

        <flux:button type="submit" variant="primary">
            {{ __('Send') }}
        </flux:button>
    </form>
</div>
