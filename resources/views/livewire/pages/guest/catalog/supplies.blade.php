<?php

use App\Enums\Content\Catalog\SupplyCategory;
use App\Models\Content\Catalog\Supply;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Volt\Component;

new class extends Component {
    public string $supplyCategory = SupplyCategory::CONSUMABLES->value;

    #[Computed]
    public function supplies(): Collection
    {
        $latestUpdate = Supply::max('updated_at');

        return cache()->remember("supplies.{$latestUpdate}", now()->addWeek(), function () {
            return Supply::all()->groupBy('category')->map(function ($items) {
                return $items->map(fn($item) => [
                    'name'        => $item->name,
                    'image'       => $item->image_path,
                    'description' => $item->description,
                    'price'       => $item->price,
                    'resource'    => $item->resource
                ]);
            });
        });
    }

    #[Computed]
    public function categories(): array
    {
        return collect(SupplyCategory::cases())
            ->filter(fn($class) => $this->supplies->has($class->value))
            ->values()
            ->toArray();
    }
}; ?>

<section class="isolate">
    @if($this->supplies()->isNotEmpty())
        <div class="text-center mb-12 space-y-4">
            <div class="flex justify-center">
                <div class="rounded-full bg-[color-mix(in_oklab,_var(--color-compliment),_transparent_90%)] p-3">
                    <flux:icon.beaker class="h-6 w-6 text-[var(--color-compliment-content)]"/>
                </div>
            </div>

            <p class="text-[var(--color-compliment-content)] !mt-2">
                {{ __('Utilities') }}
            </p>

            <flux:heading size="2xl" level="2" class="max-w-3xl mx-auto">
                {{ __('Adventure Supplies') }}
            </flux:heading>

            <flux:subheading class="mx-auto max-w-2xl leading-8">
                {{ __('Smart conveniences and helpful utilities that make your daily adventures smoother and more enjoyable.') }}
            </flux:subheading>
        </div>

        <flux:card>
            <flux:tab.group>
                <flux:tabs variant="segmented" wire:model="supplyCategory" class="w-full overflow-auto">
                    @foreach($this->categories as $category)
                        <flux:tab name="{{ $category->value }}">{{ $category->getLabel() }}</flux:tab>
                    @endforeach
                </flux:tabs>

                @foreach($this->categories as $category)
                    <flux:tab.panel name="{{ $category->value }}">
                        <div
                            class="grid max-sm:justify-self-center grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-16">
                            @foreach($this->supplies->get($category->value, []) as $supply)
                                <div class="flex items-start gap-2 h-full">
                                    <img src="{{ asset($supply['image']) }}"
                                         alt="{{ $supply['name'] }} image preview"
                                         class="w-8 h-8 object-contain">

                                    <div class="flex flex-col space-y-2 min-h-24 h-full w-full">
                                        <flux:heading>
                                            {{ $supply['name'] }}
                                        </flux:heading>

                                        <flux:text size="sm">
                                            <p>{{ $supply['description'] }}</p>
                                        </flux:text>

                                        <flux:spacer/>

                                        <x-resource-badge :value="$supply['price']"
                                                          :resource="$supply['resource']"
                                                          class="mt-auto w-fit"/>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </flux:tab.panel>
                @endforeach
            </flux:tab.group>

            <flux:text size="sm" class="mt-12">
                {{ __('All items can be found in-game within the Cash Shop.') }}
            </flux:text>
        </flux:card>
    @endif
</section>
