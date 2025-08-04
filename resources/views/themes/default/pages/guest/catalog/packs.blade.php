@php
use App\Enums\Content\Catalog\EquipmentType;
use App\Enums\Content\Catalog\EquipmentOption;
@endphp

<section class="isolate">
    @if($this->packs()->isNotEmpty())
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
                        <flux:card class="flex justify-center items-center w-full">
                            <flux:tab.group class="flex flex-col items-center  max-w-xl">
                                <flux:tabs variant="segmented" wire:model="tier" class="max-w-xs w-full">
                                    @foreach($this->availableTiersForClass($class->value) as $tier)
                                        <flux:tab name="tier-{{ $tier->value }}">{{ $tier->getLabel() }}</flux:tab>
                                    @endforeach
                                </flux:tabs>

                                @foreach($this->availableTiersForClass($class->value) as $tier)
                                    <flux:tab.panel name="tier-{{ $tier->value }}" class="w-full">
                                        @foreach($this->packs->get($class->value, []) as $pack)
                                            @if($pack->tier === $tier)
                                                <div
                                                    class="flex max-md:flex-col max-md:items-center max-md:space-y-8 w-full h-full ">
                                                    <figure
                                                        class="overflow-hidden max-w-xs w-full text-center">
                                                        <img src="{{ asset($pack->image_path) }}"
                                                             alt="Image preview of the item set which is included in this starter pack"
                                                             class="max-w-[65%] mx-auto p-8 border-t-[3px] border-r-[3px] border-l-[3px] border-zinc-200 dark:border-white/30"/>
                                                        <figcaption>
                                                            <flux:heading level="3" size="xl"
                                                                          class="w-full py-1.5 uppercase tracking-widest !font-light">
                                                                {{ explode(' ', $class->getLabel())[0] }}
                                                                <span
                                                                    class="font-black">{{ explode(' ', $class->getLabel())[1] }}</span>
                                                            </flux:heading>
                                                            <flux:text
                                                                class="max-w-[65%] mx-auto py-1.5 uppercase border-b-[3px] border-r-[3px] border-l-[3px] border-zinc-200 dark:border-white/30">
                                                                {{ $pack->tier->getLabel() }}
                                                            </flux:text>
                                                        </figcaption>

                                                        <div class="mt-6">
                                                            <x-resource-badge :value="$pack->price"
                                                                              :resource="$pack->resource"/>
                                                        </div>
                                                    </figure>

                                                    <div class="flex flex-col space-y-6 w-full h-full">
                                                        <flux:heading level="3" size="lg"
                                                                      class="flex items-center gap-2">
                                                            {{ __('Bundle Contents') }}

                                                            @if($pack->tooltip_image_path)
                                                                <flux:tooltip class="max-lg:hidden">
                                                                    <flux:icon.information-circle variant="mini"/>

                                                                    <flux:tooltip.content
                                                                        class="max-w-[20rem] space-y-2">
                                                                        <img
                                                                            src="{{ asset($pack->tooltip_image_path) }}"
                                                                            alt="Screenshot of all items included in this starter package">
                                                                    </flux:tooltip.content>
                                                                </flux:tooltip>

                                                                <flux:tooltip toggleable class="lg:hidden">
                                                                    <flux:button icon="information-circle" size="sm"
                                                                                 variant="ghost"/>

                                                                    <flux:tooltip.content
                                                                        class="max-w-[20rem] space-y-2">
                                                                        <img
                                                                            src="{{ asset($pack->tooltip_image_path) }}"
                                                                            alt="Screenshot of all items included in this starter package">
                                                                    </flux:tooltip.content>
                                                                </flux:tooltip>
                                                            @endif
                                                        </flux:heading>

                                                        <div class="flex-1 space-y-2">
                                                            @foreach($pack->contents as $item)
                                                                <flux:card class="flex items-center gap-2 py-2">
                                                                    <flux:icon
                                                                        name="{{ EquipmentType::from($item['type'])->icon() }}"
                                                                        variant="mini"/>
                                                                    <flux:text>
                                                                        {{ $item['name'] }}
                                                                    </flux:text>
                                                                </flux:card>
                                                            @endforeach
                                                        </div>

                                                        <flux:separator variant="subtle"/>

                                                        <div class="space-y-2">
                                                            <flux:subheading size="sm">
                                                                {{ __('Equipment Options') }}
                                                            </flux:subheading>

                                                            <div
                                                                class="flex items-center flex-wrap whitespace-nowrap gap-2">
                                                                @foreach(EquipmentOption::cases() as $option)
                                                                    @if($pack->hasOption($option))
                                                                        @php
                                                                            $text = $pack->getOptionDisplayText($option)
                                                                        @endphp

                                                                        @if(is_array($text))
                                                                            @foreach($text as $excellentOption)
                                                                                <flux:badge size="sm"
                                                                                            icon="{{ $option->badgeIcon() }}"
                                                                                            color="{{ $option->badgeColor() }}">
                                                                                    {{ $excellentOption }}
                                                                                </flux:badge>
                                                                            @endforeach
                                                                        @else
                                                                            <flux:badge size="sm"
                                                                                        icon="{{ $option->badgeIcon() }}"
                                                                                        color="{{ $option->badgeColor() }}">
                                                                                {{ $text }}
                                                                            </flux:badge>
                                                                        @endif
                                                                    @endif
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </flux:tab.panel>
                                @endforeach

                                <flux:text size="sm" class="mt-12 text-center">
                                    {{ __('All items can be found in-game within the Cash Shop.') }}
                                </flux:text>
                            </flux:tab.group>
                        </flux:card>
                    </div>
                </flux:tab.panel>
            @endforeach
        </flux:tab.group>
    @endif
</section>