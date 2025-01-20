<?php

use Livewire\Volt\Component;
use App\Models\Content\Catalog\Buff;
use Livewire\Attributes\Url;
use Livewire\Attributes\Computed;
use App\Enums\Content\Catalog\BuffDuration;
use Illuminate\Support\Collection;

new class extends Component {
    #[Url(as: 'duration')]
    public int $buffDuration = 7;

    #[Computed]
    public function bundles(): Collection
    {
        $bundles = Buff::where('is_bundle', true)->get();

        return $bundles->flatMap(function ($bundle) {
            // For each bundle, create duration variants
            return collect(BuffDuration::cases())->map(function ($duration) use ($bundle) {
                $price = collect($bundle->duration_prices)
                    ->firstWhere('duration', (string) $duration->value)['price'] ?? null;

                if ( ! $price) return null;

                return [
                    'name'         => $bundle->name,
                    'image'        => $bundle->image_path,
                    'bundle_items' => $bundle->bundle_items,
                    'duration'     => $duration->getLabel(),
                    'price'        => $price
                ];
            });
        })->filter()->values();
    }

    #[Computed]
    public function durations(): array
    {
        return BuffDuration::cases();
    }

    #[Computed]
    public function weekBuffs(): Collection
    {
        return $this->getBuffsForDuration('7');
    }

    #[Computed]
    public function twoWeekBuffs(): Collection
    {
        return $this->getBuffsForDuration('14');
    }

    #[Computed]
    public function monthBuffs(): Collection
    {
        return $this->getBuffsForDuration('30');
    }

    private function getBuffsForDuration(string $duration): Collection
    {
        return Buff::where('is_bundle', false)
            ->get()
            ->map(function ($buff) use ($duration) {
                $price = collect($buff->duration_prices)
                    ->firstWhere('duration', $duration)['price'] ?? null;

                return [
                    'name'  => $buff->name,
                    'image' => $buff->image_path,
                    'stats' => $buff->stats,
                    'price' => $price
                ];
            })
            ->filter(fn($buff) => ! is_null($buff['price']));
    }
}; ?>

<section class="isolate">
    <div class="text-center mb-12 space-y-4">
        <div class="flex justify-center">
            <div class="rounded-full bg-[color-mix(in_oklab,_var(--color-compliment),_transparent_90%)] p-3">
                <flux:icon.wand-sparkles class="h-6 w-6 text-[var(--color-compliment-content)]"/>
            </div>
        </div>

        <p class="text-[var(--color-compliment-content)] !mt-2">
            {{ __('Enhancements') }}
        </p>

        <flux:heading size="2xl" level="2" class="max-w-3xl mx-auto">
            {{ __('Buffs & Boosts') }}
        </flux:heading>

        <flux:subheading class="mx-auto max-w-2xl leading-8">
            {{ __('Ancient powers adapted for modern battles. Enhance your gameplay with carefully balanced buffs that
            respect the core experience.') }}
        </flux:subheading>
    </div>

    <flux:card>
        <flux:tab.group>
            <flux:tabs variant="segmented" wire:model="buffDuration" class="w-full max-sm:hidden">
                @foreach($this->durations as $duration)
                    <flux:tab name="{{ $duration->value }}" icon="clock">
                        {{ $duration->getLabel() }}
                    </flux:tab>
                @endforeach
            </flux:tabs>

            <flux:tabs variant="segmented" size="sm" wire:model="buffDuration" class="w-full sm:hidden">
                @foreach($this->durations as $duration)
                    <flux:tab name="{{ $duration->value }}">
                        {{ $duration->getLabel() }}
                    </flux:tab>
                @endforeach
            </flux:tabs>

            @foreach($this->durations as $duration)
                <flux:tab.panel name="{{ $duration->value }}">
                    <div class="grid max-sm:justify-self-center grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-16">
                        @foreach(match($duration) {
                            BuffDuration::WEEK => $this->weekBuffs,
                            BuffDuration::TWO_WEEKS => $this->twoWeekBuffs,
                            BuffDuration::MONTH => $this->monthBuffs,
                        } as $buff)
                            <div class="flex items-start gap-2">
                                <img src="{{ asset($buff['image']) }}" class="w-24 h-24 object-contain">

                                <div class="flex flex-col space-y-2 min-h-24">
                                    <flux:heading>
                                        {{ $buff['name'] }}
                                    </flux:heading>

                                    <flux:text size="sm">
                                        @foreach($buff['stats'] as $stat)
                                            <p>{{ $stat['value'] }}</p>
                                        @endforeach
                                    </flux:text>

                                    <flux:spacer/>

                                    <flux:badge variant="pill" color="teal" size="sm" class="mt-auto w-fit">
                                        {{ $buff['price'] }} Credits
                                    </flux:badge>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </flux:tab.panel>
            @endforeach
        </flux:tab.group>

        <flux:separator class="my-16" variant="subtle"/>

        <div class="grid max-sm:justify-self-center grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-16">
            @foreach($this->bundles as $bundle)
                <div class="flex items-start gap-2">
                    <img src="{{ asset($bundle['image']) }}"
                         class="w-20 h-20 object-contain">

                    <div class="flex flex-col space-y-2 min-h-24">
                        <flux:heading>
                            {{ $bundle['name'] }} - {{ $bundle['duration'] }}
                        </flux:heading>
                        <flux:text size="sm">
                            @foreach($bundle['bundle_items'] as $item)
                                <li class="list-disc ml-2">{{ $item['name'] }}</li>
                            @endforeach
                        </flux:text>

                        <flux:spacer/>

                        <flux:badge variant="pill" color="teal" size="sm" class="mt-auto w-fit">
                            {{ $bundle['price'] }} Credits
                        </flux:badge>
                    </div>
                </div>
            @endforeach
        </div>

        <flux:text size="sm" class="mt-12">
            All items can be found in-game within the Cash Shop.
        </flux:text>
    </flux:card>
</section>
