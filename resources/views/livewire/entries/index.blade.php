<?php

use App\Enums\Game\AccountLevel;
use App\Enums\Utility\ActivityType;
use App\Models\Game\Character;
use App\Models\Game\Entry;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\Auth;

new #[Layout('layouts.app')] class extends Component {
    protected array $entryCountsCache = [];

    const EVENT_TYPE_BLOOD_CASTLE = 3;
    const EVENT_TYPE_DEVIL_SQUARE = 5;

    #[Computed]
    public function characters()
    {
        return Character::query()
            ->select('Name', 'cLevel', 'ResetCount', 'Class')
            ->where('AccountID', auth()->user()->name)
            ->with([
                'entries' => function ($query) {
                    $query->whereIn('Type', [self::EVENT_TYPE_BLOOD_CASTLE, self::EVENT_TYPE_DEVIL_SQUARE]);
                }
            ])
            ->get();
    }

    public function getEntryCount(Character $character, int $type)
    {
        if ( ! isset($this->entryCountsCache[$character->Name])) {
            $this->entryCountsCache[$character->Name] = $character->entries->pluck('EntryCount', 'Type')->toArray();
        }

        return $this->entryCountsCache[$character->Name][$type] ?? 0;
    }

    #[Computed]
    public function isVip(): bool
    {
        return Auth::user()->member->AccountLevel !== AccountLevel::Regular;
    }

    #[Computed]
    public function maxEntries(): int
    {
        return $this->isVip() ? 4 : 3;
    }

    public function getEntryText($count, $max)
    {
        $text = "{$count}/{$max}";

        return $count >= $max ? "<span class='text-red-500'>{$text}</span>" : $text;
    }
}; ?>

<div class="space-y-6">
    <header>
        <flux:heading size="xl">
            {{ __('Event Entries') }}
        </flux:heading>

        <x-flux::subheading>
            {{ __('Review the number of times you\'ve registered for events with attendance limitations.') }}
        </x-flux::subheading>
    </header>

    <div class="flex max-sm:flex-col items-start gap-8 w-full">
        <flux:card class="w-full space-y-6">
            <flux:heading size="lg">
                Blood Castle
            </flux:heading>
            <flux:table>
                <flux:rows>
                    @foreach ($this->characters as $character)
                        <flux:row :key="$character->Name">
                            <flux:cell class="flex items-center gap-3">
                                <flux:avatar size="xs" src="{{ asset($character->Class->getImagePath()) }}"/>
                                <span>{{ $character->Name }}</span>
                            </flux:cell>

                            <flux:cell variant="strong">
                                {!! $this->getEntryText($this->getEntryCount($character, self::EVENT_TYPE_BLOOD_CASTLE), $this->maxEntries()) !!}
                            </flux:cell>
                        </flux:row>
                    @endforeach
                </flux:rows>
            </flux:table>
        </flux:card>

        <flux:card class="w-full space-y-6">
            <flux:heading size="lg">
                Devil Square
            </flux:heading>
            <flux:table>
                <flux:rows>
                    @foreach ($this->characters as $character)
                        <flux:row :key="$character->Name">
                            <flux:cell class="flex items-center gap-3">
                                <flux:avatar size="xs" src="{{ asset($character->Class->getImagePath()) }}"/>
                                <span>{{ $character->Name }}</span>
                            </flux:cell>

                            <flux:cell variant="strong">
                                {!! $this->getEntryText($this->getEntryCount($character, self::EVENT_TYPE_DEVIL_SQUARE), $this->maxEntries()) !!}
                            </flux:cell>
                        </flux:row>
                    @endforeach
                </flux:rows>
            </flux:table>
        </flux:card>
    </div>
</div>
