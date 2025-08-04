<?php

namespace App\Livewire\Pages\Guest\Catalog;

use App\Enums\Content\Catalog\PackTier;
use App\Enums\Game\CharacterClass;
use App\Models\Content\Catalog\Pack;
use App\Livewire\BaseComponent;
use Livewire\Attributes\Computed;
use App\Enums\Content\Catalog\EquipmentType;
use App\Enums\Content\Catalog\EquipmentOption;

class Packs extends BaseComponent
{
    public int $selectedClass;

    public function mount()
    {
        if ($this->characterClasses) {
            $this->selectedClass = $this->characterClasses[0]->value;
        }
    }

    #[Computed]
    public function packs()
    {
        $latestUpdate = Pack::max('updated_at');

        return cache()->remember("packs.{$latestUpdate}", now()->addWeek(), function () {
            return Pack::all()
                ->groupBy('character_class');
        });
    }

    #[Computed]
    public function characterClasses(): array
    {
        return collect(CharacterClass::cases())
            ->filter(fn($class) => $this->packs->has($class->value))
            ->values()
            ->toArray();
    }

    public string $tier = 'tier-1';

    #[Computed]
    public function packsByClass()
    {
        return $this->packs->get($this->selectedClass, collect())->groupBy('tier');
    }

    #[Computed]
    public function tiers(): array
    {
        return PackTier::cases();
    }

    #[Computed]
    public function availableTiersForClass(int $classId): array
    {
        return $this->packs
            ->get($classId, collect())
            ->pluck('tier')
            ->unique()
            ->values()
            ->toArray();
    }

    protected function getViewName(): string
    {
        return 'pages.guest.catalog.packs';
    }

    protected function getLayoutType(): string
    {
        return 'guest';
    }
}