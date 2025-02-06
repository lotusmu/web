<?php

use App\Enums\Utility\ResourceType;
use App\Models\Content\Download;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
//
}; ?>

<flux:main container>
    <x-page-header
        title="Who's on top?"
        kicker="Rankings"
        description="The numbers don’t lie — players and guilds ranked by their achievements."
    />

    <flux:card class="max-w-xl space-y-12 mx-auto">
        <div>
            <flux:heading size="lg">
                {{ __('Weekly Rankings Archive') }}
            </flux:heading>
            <flux:subheading>
                {{ __('View past rankings and their rewards.') }}
            </flux:subheading>
        </div>

        <flux:tab.group>
            <flux:tabs variant="segmented" wire:model="tab" class="w-full">
                <flux:tab name="events">Events Archive</flux:tab>
                <flux:tab name="hunters">Hunt Archive</flux:tab>
            </flux:tabs>

            <flux:tab.panel name="events">
                <flux:accordion transition>
                    <flux:accordion.item>
                        <flux:accordion.heading>
                            <div class="flex items-center gap-2">
                                <flux:icon.calendar-date-range variant="mini"/>

                                <span>Feb 6, 2025 - Feb 13, 2025</span>
                            </div>
                        </flux:accordion.heading>

                        <flux:accordion.content>
                            <flux:table>
                                <flux:columns>
                                    <flux:column>#</flux:column>

                                    <flux:column>
                                        {{ __('Character') }}
                                    </flux:column>

                                    <flux:column>
                                        {{ __('Score') }}
                                    </flux:column>

                                    <flux:column>
                                        {{ __('Reward') }}
                                    </flux:column>
                                </flux:columns>

                                <flux:rows>
                                    @foreach (range(1, 30) as $i)
                                        <flux:row>
                                            <flux:cell>1.</flux:cell>

                                            <flux:cell>
                                                {{ 'HEROINa' }}
                                            </flux:cell>

                                            <flux:cell>
                                                12,500
                                            </flux:cell>

                                            <flux:cell class="space-x-1">
                                                <x-resource-badge value="100000000"
                                                                  :resource="ResourceType::ZEN"/>

                                                <x-resource-badge value="1000"
                                                                  :resource="ResourceType::CREDITS"/>
                                            </flux:cell>
                                        </flux:row>
                                    @endforeach
                                </flux:rows>
                            </flux:table>
                        </flux:accordion.content>
                    </flux:accordion.item>
                </flux:accordion>
            </flux:tab.panel>

            <flux:tab.panel name="hunters">
                <flux:accordion transition>
                    <flux:accordion.item>
                        <flux:accordion.heading>
                            <div class="flex items-center gap-2">
                                <flux:icon.calendar-date-range variant="mini"/>

                                <span>Mar 6, 2025 - Mar 13, 2025</span>
                            </div>
                        </flux:accordion.heading>

                        <flux:accordion.content>
                            <flux:table>
                                <flux:columns>
                                    <flux:column>#</flux:column>

                                    <flux:column>
                                        {{ __('Character') }}
                                    </flux:column>

                                    <flux:column>
                                        {{ __('Score') }}
                                    </flux:column>

                                    <flux:column>
                                        {{ __('Reward') }}
                                    </flux:column>
                                </flux:columns>

                                <flux:rows>
                                    @foreach (range(1, 30) as $i)
                                        <flux:row>
                                            <flux:cell>1.</flux:cell>

                                            <flux:cell>
                                                {{ 'HEROINa' }}
                                            </flux:cell>

                                            <flux:cell>
                                                12,500
                                            </flux:cell>

                                            <flux:cell class="space-x-1">
                                                <x-resource-badge value="100000000"
                                                                  :resource="ResourceType::ZEN"/>

                                                <x-resource-badge value="1000"
                                                                  :resource="ResourceType::CREDITS"/>
                                            </flux:cell>
                                        </flux:row>
                                    @endforeach
                                </flux:rows>
                            </flux:table>
                        </flux:accordion.content>
                    </flux:accordion.item>
                </flux:accordion>
            </flux:tab.panel>
        </flux:tab.group>


    </flux:card>
</flux:main>

