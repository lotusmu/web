<?php

use App\Enums\Content\Catalog\PackTier;
use App\Enums\Game\CharacterClass;
use App\Models\Content\Catalog\Pack;
use Livewire\Attributes\Computed;
use Livewire\Volt\Component;
use App\Enums\Content\Catalog\EquipmentType;
use App\Enums\Content\Catalog\EquipmentOption;

new class extends Component {
    public int $selectedClass;

    public function mount()
    {
        // Set initial tab to first available class
        $this->selectedClass = $this->characterClasses[0]->value;
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

    public function getIconForType(string $type): string
    {
        return match ($type) {
            EquipmentType::WEAPON->value => 'sword',
            EquipmentType::ITEM_SET->value => 'shield',
            EquipmentType::ACCESSORY->value => 'ribbon',
            EquipmentType::CONSUMABLE->value => 'beaker'
        };
    }
}; ?>

<section class="isolate">
    <div class="text-center mb-12 space-y-4">
        <div class="flex justify-center">
            <div class="rounded-full bg-[color-mix(in_oklab,_var(--color-compliment),_transparent_90%)] p-3">
                <flux:icon.swords class="h-6 w-6 text-[var(--color-compliment-content)]"/>
            </div>
        </div>

        <p class="text-[var(--color-compliment-content)] !mt-2">
            {{ __('Starter Packs') }}
        </p>

        <flux:heading size="2xl" level="2" class="max-w-3xl mx-auto">
            {{ __('Item Bundles') }}
        </flux:heading>

        <flux:subheading class="mx-auto max-w-2xl leading-8">
            {{ __('Curated starter bundles that give you exactly what you need. Nothing more.') }}
        </flux:subheading>
    </div>

    <flux:tab.group>
        <flux:tabs variant="pills" wire:model="selectedClass" class="flex overflow-auto sm:mx-0 sm:justify-center">
            @foreach($this->characterClasses as $class)
                <flux:tab name="{{ $class->value }}" :accent="false">
                    {{ $class->getLabel() }}
                </flux:tab>
            @endforeach
        </flux:tabs>

        @foreach($this->characterClasses as $class)
            <flux:tab.panel name="{{ $class->value }}">
                <div class="flex items-stretch justify-center max-xl:flex-col gap-8 w-full">
                    @foreach($this->packs->get($class->value, []) as $pack)
                        <flux:card class="flex w-full max-md:flex-col max-md:items-center max-md:space-y-8">
                            <!-- Pack Image and Title -->
                            <figure class="overflow-hidden max-w-xs w-full text-center">
                                <img src="{{ asset($pack->image_path) }}"
                                     class="max-w-[65%] mx-auto p-8 rounded-tl-xl rounded-tr-xl border-t-[3px] border-r-[3px] border-l-[3px] border-zinc-200 dark:border-white/30"/>
                                <figcaption>
                                    <flux:heading level="3" size="xl"
                                                  class="w-full py-1.5 uppercase tracking-widest !font-light">
                                        <!-- Split the class name into parts -->
                                        {{ explode(' ', $class->getLabel())[0] }}
                                        <span class="font-black">{{ explode(' ', $class->getLabel())[1] }}</span>
                                    </flux:heading>
                                    <flux:text
                                        class="max-w-[65%] mx-auto py-1.5 uppercase rounded-bl-xl rounded-br-xl border-b-[3px] border-r-[3px] border-l-[3px] border-zinc-200 dark:border-white/30">
                                        {{ $pack->tier->getLabel() }}
                                    </flux:text>
                                </figcaption>
                            </figure>

                            <div class="flex flex-col space-y-6 w-full">
                                <flux:heading level="3" size="lg">
                                    {{ __('Bundle Contents') }}
                                </flux:heading>

                                <div class="space-y-2">
                                    @foreach($pack->contents as $item)
                                        <flux:card class="flex items-center gap-2 py-2">
                                            <flux:icon name="{{ EquipmentType::from($item['type'])->icon() }}"
                                                       variant="mini"/>
                                            <flux:text>
                                                {{ $item['name'] }}
                                            </flux:text>
                                        </flux:card>
                                    @endforeach
                                </div>

                                <flux:separator variant="subtle"/>

                                <!-- Equipment Section -->
                                <flux:heading level="3" size="lg">
                                    {{ __('Equipment Options') }}
                                </flux:heading>

                                <!-- Badges for options -->
                                <div class="space-y-2">
                                    <div class="flex items-center flex-wrap whitespace-nowrap gap-2">
                                        @foreach(EquipmentOption::cases() as $option)
                                            @if($pack->hasOption($option))
                                                <flux:badge size="sm" icon="{{ $option->badgeIcon() }}"
                                                            color="{{ $option->badgeColor() }}">
                                                    @if($value = $pack->getOptionValue($option))
                                                        @if($option === EquipmentOption::ADDITIONAL)
                                                            Additional +{{ $value }}
                                                        @else
                                                            {{ $value }}
                                                        @endif
                                                    @else
                                                        {{ $option->getLabel() }}
                                                    @endif
                                                </flux:badge>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>

                                <flux:spacer/>
                                
                                <!-- Price -->
                                <div>
                                    <flux:badge variant="pill" size="sm" color="teal">
                                        {{ $pack->price }} {{ strtolower($pack->resource->value) }}
                                    </flux:badge>
                                </div>
                            </div>
                        </flux:card>
                    @endforeach
                </div>
            </flux:tab.panel>
        @endforeach
    </flux:tab.group>

</section>
