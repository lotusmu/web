<?php

namespace App\Livewire\Pages\App\Entries;

use App\Livewire\BaseComponent;
use App\Models\Game\Character;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;

class Entries extends BaseComponent
{
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
                },
            ])
            ->get();
    }

    public function getEntryCount(Character $character, int $type)
    {
        if (! isset($this->entryCountsCache[$character->Name])) {
            $this->entryCountsCache[$character->Name] = $character->entries->pluck('EntryCount', 'Type')->toArray();
        }

        return $this->entryCountsCache[$character->Name][$type] ?? 0;
    }

    #[Computed]
    public function isVip(): bool
    {
        return Auth::user()->hasValidVipSubscription();
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

    protected function getViewName(): string
    {
        return 'pages.app.entries.index';
    }

    protected function getLayoutType(): string
    {
        return 'app';
    }
}
