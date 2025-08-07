<?php

namespace App\Livewire\Pages\App\Wallet;

use App\Actions\Wallet\SendResources;
use App\Enums\Utility\OperationType;
use App\Enums\Utility\ResourceType;
use App\Livewire\BaseComponent;
use App\Models\Concerns\Taxable;
use App\Models\Game\Character;
use App\Models\User\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Enum;

class SendGift extends BaseComponent
{
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
        $this->sender = Auth::user()->id;
        $this->operationType = OperationType::TRANSFER;
        $this->initializeTaxable();
    }

    public function rules(): array
    {
        return [
            'recipient' => 'required|string|min:4|max:10',
            'resourceType' => ['required', new Enum(ResourceType::class)],
            'amount' => 'required|integer|min:100',
        ];
    }

    public function transfer(SendResources $action): void
    {
        $this->validate();

        $recipientUser = Character::findUserByCharacterName($this->recipient);

        if (! $recipientUser) {
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
            fn () => Character::query()
                ->select('name')
                ->where('name', 'like', $searchTerm.'%')
                ->orderBy('name')
                ->limit(self::MAX_SEARCH_RESULTS)
                ->pluck('name')
                ->toArray()
        );
    }

    protected function getViewName(): string
    {
        return 'pages.app.wallet.send-gift';
    }

    protected function getLayoutType(): string
    {
        return 'app';
    }
}
