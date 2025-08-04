<?php

namespace App\Livewire\Pages\Guest\Catalog;

use App\Enums\Content\Catalog\BuffDuration;
use App\Livewire\BaseComponent;
use App\Models\Content\Catalog\Buff;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;

class Buffs extends BaseComponent
{
    public int $buffDuration = BuffDuration::WEEK->value;

    #[Computed]
    public function allBundles(): Collection
    {
        $latestUpdate = Buff::max('updated_at');

        return cache()->remember("buff_bundles.{$latestUpdate}", now()->addWeek(), function () {
            return Buff::where('is_bundle', true)->get();
        });
    }

    #[Computed]
    public function bundles(): Collection
    {
        return $this->allBundles->flatMap(function ($bundle) {
            return collect(BuffDuration::cases())->map(function ($duration) use ($bundle) {
                $price = collect($bundle->duration_prices)
                    ->firstWhere('duration', (string) $duration->value)['price'] ?? null;

                return $price ? [
                    'name'         => $bundle->name,
                    'image'        => $bundle->image_path,
                    'bundle_items' => $bundle->bundle_items,
                    'duration'     => $duration->getLabel(),
                    'price'        => $price,
                    'resource'     => $bundle->resource
                ] : null;
            });
        })->filter()->values();
    }

    #[Computed]
    public function durations(): array
    {
        return BuffDuration::cases();
    }

    #[Computed]
    public function buffsByDuration(): array
    {
        return [
            '7'  => $this->getBuffsForDuration('7'),
            '14' => $this->getBuffsForDuration('14'),
            '30' => $this->getBuffsForDuration('30')
        ];
    }

    #[Computed]
    public function buffs(): Collection
    {
        $latestUpdate = Buff::max('updated_at');

        return cache()->remember("buffs.{$latestUpdate}", now()->addWeek(), function () {
            return Buff::where('is_bundle', false)->get();
        });
    }

    public function getBuffsForDuration(string $duration): Collection
    {
        return $this->buffs->map(function ($buff) use ($duration) {
            $price = collect($buff->duration_prices)
                ->firstWhere('duration', $duration)['price'] ?? null;

            return [
                'name'     => $buff->name,
                'image'    => $buff->image_path,
                'stats'    => $buff->stats,
                'price'    => $price,
                'resource' => $buff->resource
            ];
        })->filter(fn($buff) => ! is_null($buff['price']));
    }

    protected function getViewName(): string
    {
        return 'pages.guest.catalog.buffs';
    }

    protected function getLayoutType(): string
    {
        return 'guest';
    }
}
