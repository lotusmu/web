<section class="isolate">
    @if($this->bundles()->isNotEmpty() || $this->buffs()->isNotEmpty())
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
                {{ __('Ancient powers adapted for modern battles. Enhance your gameplay with carefully balanced buffs that respect the core experience.') }}
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
                    <flux:tab.panel name="{{ $duration->value }}" :lazy="true">
                        <div class="grid max-sm:justify-self-center grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-16">
                            @foreach($this->buffsByDuration[$duration->value] as $buff)
                                <div class="flex items-start gap-2 h-full">
                                    <img src="{{ asset($buff['image']) }}" class="w-24 h-24 object-contain"
                                         alt="{{ $buff['name'] }} image preview">

                                    <div class="flex flex-col space-y-2 h-full w-full">
                                        <flux:heading>
                                            {{ $buff['name'] }}
                                        </flux:heading>

                                        <flux:text size="sm">
                                            @foreach($buff['stats'] as $stat)
                                                <p>{{ $stat['value'] }}</p>
                                            @endforeach
                                        </flux:text>

                                        <flux:spacer/>

                                        <x-resource-badge :value="$buff['price']"
                                                          :resource="$buff['resource']"
                                                          class="mt-auto w-fit"/>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </flux:tab.panel>
                @endforeach
            </flux:tab.group>

            @if($this->bundles->isNotEmpty() && $this->buffs->isNotEmpty())
                <flux:separator class="my-16" variant="subtle"/>
            @endif

            <div class="grid max-sm:justify-self-center grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-16">
                @foreach($this->bundles as $bundle)
                    <div class="flex items-start gap-2 h-full">
                        <img src="{{ asset($bundle['image']) }}" alt="{{ $bundle['name'] }} image preview"
                             class="w-20 h-20 object-contain">

                        <div class="flex flex-col space-y-2 h-full w-full">
                            <flux:heading>
                                {{ $bundle['name'] }} - {{ $bundle['duration'] }}
                            </flux:heading>
                            <flux:text size="sm">
                                @foreach($bundle['bundle_items'] as $item)
                                    <li class="list-disc ml-2">{{ $item }}</li>
                                @endforeach
                            </flux:text>

                            <flux:spacer/>

                            <x-resource-badge :value="$bundle['price']"
                                              :resource="$bundle['resource']"
                                              class="mt-auto w-fit"/>
                        </div>
                    </div>
                @endforeach
            </div>

            <flux:text size="sm" class="mt-12">
                {{ __('All items can be found in-game within the Cash Shop.') }}
            </flux:text>
        </flux:card>
    @endif
</section>
