<?php

namespace App\Livewire\Pages\Guest\Catalog;

use App\Enums\Content\Catalog\SupplyCategory;
use App\Livewire\BaseComponent;
use App\Models\Content\Catalog\Supply;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;

class Supplies extends BaseComponent
{
    public string $supplyCategory = SupplyCategory::CONSUMABLES->value;

    #[Computed]
    public function supplies(): Collection
    {
        $latestUpdate = Supply::max('updated_at');

        return cache()->remember("supplies.{$latestUpdate}", now()->addWeek(), function () {
            return Supply::all()->groupBy('category')->map(function ($items) {
                return $items->map(fn ($item) => [
                    'name' => $item->name,
                    'image' => $item->image_path,
                    'description' => $item->description,
                    'price' => $item->price,
                    'resource' => $item->resource,
                ]);
            });
        });
    }

    #[Computed]
    public function categories(): array
    {
        return collect(SupplyCategory::cases())
            ->filter(fn ($class) => $this->supplies->has($class->value))
            ->values()
            ->toArray();
    }

    protected function getViewName(): string
    {
        return 'pages.guest.catalog.supplies';
    }

    protected function getLayoutType(): string
    {
        return 'guest';
    }
}
