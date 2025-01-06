<?php

use App\Models\Content\Download;
use App\Models\Utility\GameServer;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    #[\Livewire\Attributes\Url]
    public string $tab = '';

    public function getServersProperty(): Collection
    {
        return GameServer::where('is_active', true)
            ->get()
            ->map(function ($server) {
                $server->reset_zen    = Number::abbreviate($server->reset_zen);
                $server->clear_pk_zen = Number::abbreviate($server->clear_pk_zen);

                return $server;
            });
    }

    public function mount(): void
    {
        // Set first server as default tab
        if ($this->servers->isNotEmpty()) {
            $this->tab = $this->servers->first()->name;
        }
    }
}; ?>

<flux:main container>
    <x-page-hero
        title="A peek at the server basics"
        kicker="Server Overview"
        description="Check out our core server settings, experience rates, and basic configuration details."
    />

    <div class="space-y-6 max-w-4xl mx-auto">
        <x-info-card color="teal" icon="book-open">
            <flux:text>
                {{ __('Everything about Lotus Mu is documented in') }}
                <flux:link href="https://wiki.lotusmu.org" external>
                    {{ ' ' . __('our wiki') }}</flux:link>
                {{ __(' - from server mechanics to events and features!') }}
            </flux:text>
        </x-info-card>

        <flux:tab.group>
            <flux:tabs variant="pills" wire:model="tab" class="justify-center">
                @foreach($this->servers as $server)
                    <flux:tab :name="$server->name">{{ $server->getServerName() }}</flux:tab>
                @endforeach
            </flux:tabs>

            @foreach($this->servers as $server)
                <flux:tab.panel :name="$server->name" class="space-y-6">
                    <flux:card class="space-y-6">
                        <flux:heading size="lg">
                            {{ __('General Information') }}
                        </flux:heading>

                        <flux:table>
                            <flux:rows>
                                <flux:row>
                                    <flux:cell>{{ __('Server Version') }}</flux:cell>
                                    <flux:cell>{{ $server->server_version }}</flux:cell>
                                </flux:row>
                                <flux:row>
                                    <flux:cell>{{ __('Available Character Classes') }}</flux:cell>
                                    <flux:cell>DK, DW, FE, MG, DL</flux:cell>
                                </flux:row>
                                <flux:row>
                                    <flux:cell>{{ __('Experience') }}</flux:cell>
                                    <flux:cell>
                                        x{{ $server->experience_rate }},
                                        <flux:link href="https://wiki.lotusmu.org/gameplay-systems/reset-system">
                                            {{ __('decreases with resets') }}
                                        </flux:link>
                                    </flux:cell>
                                </flux:row>
                                <flux:row>
                                    <flux:cell>{{ __('Item Drop') }}</flux:cell>
                                    <flux:cell>40%</flux:cell>
                                </flux:row>
                                <flux:row>
                                    <flux:cell>{{ __('Max Resets') }}</flux:cell>
                                    <flux:cell>
                                        {{ $server->max_resets }},
                                        <flux:link href="https://wiki.lotusmu.org/gameplay-systems/reset-system">
                                            {{ __('starts at :starting', ['starting' => $server->starting_resets]) }}
                                        </flux:link>
                                    </flux:cell>
                                </flux:row>
                            </flux:rows>
                        </flux:table>
                    </flux:card>

                    <flux:card class="space-y-6">
                        <flux:heading size="lg">
                            {{ __('Reset System') }}
                        </flux:heading>

                        <flux:table>
                            <flux:rows>
                                <flux:row>
                                    <flux:cell>{{ __('Keep Stats') }}</flux:cell>
                                    <flux:cell>{{ __('No') }}</flux:cell>
                                </flux:row>
                                <flux:row>
                                    <flux:cell>{{ __('Reset Points') }}</flux:cell>
                                    <flux:cell>
                                        <flux:link href="https://wiki.lotusmu.org/gameplay-systems/reset-system/">
                                            {{ __('Dynamic') }}
                                        </flux:link>
                                    </flux:cell>
                                </flux:row>
                                <flux:row>
                                    <flux:cell>{{ __('Reset Zen') }}</flux:cell>
                                    <flux:cell>
                                        {{ __(':zen x Reset Number', ['zen' => $server->reset_zen]) }}
                                    </flux:cell>
                                </flux:row>
                                <flux:row>
                                    <flux:cell>{{ __('Clear PK Zen') }}</flux:cell>
                                    <flux:cell>
                                        {{ __(':zen x Number of Kills', ['zen' => $server->clear_pk_zen]) }}
                                    </flux:cell>
                                </flux:row>
                                <flux:row>
                                    <flux:cell>{{ __('Stat Points Per Level') }}</flux:cell>
                                    <flux:cell>{{ __('BK/SM/ME - 5 MG/DL - 7') }}</flux:cell>
                                </flux:row>
                            </flux:rows>
                        </flux:table>
                    </flux:card>

                    <flux:card class="space-y-6">
                        <flux:heading size="lg">
                            {{ __('Party Experience Bonus') }}
                        </flux:heading>

                        <flux:table>
                            <flux:columns>
                                <flux:column>{{ __('Members') }}</flux:column>
                                <flux:column>{{ __('General') }}</flux:column>
                                <flux:column>{{ __('Character Set') }}</flux:column>
                            </flux:columns>
                            <flux:rows>
                                <flux:row>
                                    <flux:cell>{{ __('Two') }}</flux:cell>
                                    <flux:cell>+2% EXP</flux:cell>
                                    <flux:cell>+5% EXP</flux:cell>
                                </flux:row>
                                <flux:row>
                                    <flux:cell>{{ __('Three') }}</flux:cell>
                                    <flux:cell>+5% EXP</flux:cell>
                                    <flux:cell>+10% EXP</flux:cell>
                                </flux:row>
                                <flux:row>
                                    <flux:cell>{{ __('Four') }}</flux:cell>
                                    <flux:cell>+7% EXP</flux:cell>
                                    <flux:cell>+15% EXP</flux:cell>
                                </flux:row>
                                <flux:row>
                                    <flux:cell>{{ __('Five') }}</flux:cell>
                                    <flux:cell>+10% EXP</flux:cell>
                                    <flux:cell>+20% EXP</flux:cell>
                                </flux:row>
                            </flux:rows>
                        </flux:table>

                        <flux:text size="sm">
                            {{ __('To form a Character Set Party, your party must consist of different character classes.') }}
                        </flux:text>
                    </flux:card>

                    <flux:card class="space-y-6">
                        <flux:heading size="lg">
                            {{ __('Game Settings') }}
                        </flux:heading>

                        <flux:table>
                            <flux:rows>
                                <flux:row>
                                    <flux:cell>{{ __('Max Excellent Options') }}</flux:cell>
                                    <flux:cell>6</flux:cell>
                                </flux:row>
                                <flux:row>
                                    <flux:cell>{{ __('Max Game Client instances') }}</flux:cell>
                                    <flux:cell>3</flux:cell>
                                </flux:row>
                                <flux:row>
                                    <flux:cell>{{ __('Max Guild Members') }}</flux:cell>
                                    <flux:cell>{{ __('30 (35 with Dark Lord as a Guild Master)') }}</flux:cell>
                                </flux:row>
                                <flux:row>
                                    <flux:cell>{{ __('Guild Alliance') }}</flux:cell>
                                    <flux:cell>{{ __('No') }}</flux:cell>
                                </flux:row>
                                <flux:row>
                                    <flux:cell>{{ __('Web Shop') }}</flux:cell>
                                    <flux:cell>{{ __('No') }}</flux:cell>
                                </flux:row>
                                <flux:row>
                                    <flux:cell>{{ __('Combo all Class') }}</flux:cell>
                                    <flux:cell>{{ __('Yes') }}</flux:cell>
                                </flux:row>
                            </flux:rows>
                        </flux:table>
                    </flux:card>

                    <flux:card class="space-y-6">
                        <flux:heading size="lg">
                            {{ __('Commands') }}
                        </flux:heading>

                        <flux:table>
                            <flux:columns>
                                <flux:column>{{ __('Command') }}</flux:column>
                                <flux:column>{{ __('Description') }}</flux:column>
                            </flux:columns>
                            <flux:rows>
                                <flux:row>
                                    <flux:cell>/move &lt;map name&gt;</flux:cell>
                                    <flux:cell>{{ __('Moves your character to a certain map.') }}</flux:cell>
                                </flux:row>
                                <flux:row>
                                    <flux:cell>/post &lt;message&gt;</flux:cell>
                                    <flux:cell>{{ __('Post a global message to the server.') }}</flux:cell>
                                </flux:row>
                                <flux:row>
                                    <flux:cell>/str &lt;value&gt;</flux:cell>
                                    <flux:cell>{{ __('Add points in Strength.') }}</flux:cell>
                                </flux:row>
                                <flux:row>
                                    <flux:cell>/agi &lt;value&gt;</flux:cell>
                                    <flux:cell>{{ __('Add points in Agility.') }}</flux:cell>
                                </flux:row>
                                <flux:row>
                                    <flux:cell>/sta &lt;value&gt;</flux:cell>
                                    <flux:cell>{{ __('Add points in Stamina.') }}</flux:cell>
                                </flux:row>
                                <flux:row>
                                    <flux:cell>/ene &lt;value&gt;</flux:cell>
                                    <flux:cell>{{ __('Add points in Energy.') }}</flux:cell>
                                </flux:row>
                                <flux:row>
                                    <flux:cell>/cmd &lt;value&gt;</flux:cell>
                                    <flux:cell>{{ __('Add points in Command.') }}</flux:cell>
                                </flux:row>
                                <flux:row>
                                    <flux:cell>/readd</flux:cell>
                                    <flux:cell>{!! __('Re-assign your stats for <b>FREE</b> (available to <b>VIP Players</b> only).') !!}</flux:cell>
                                </flux:row>
                                <flux:row>
                                    <flux:cell>/wh</flux:cell>
                                    <flux:cell>{!! __('Open warehouse in safe zones (available to <b>VIP Players</b> only).') !!}</flux:cell>
                                </flux:row>
                                <flux:row>
                                    <flux:cell>/pkclear</flux:cell>
                                    <flux:cell>{!! __('Clear player kills (available to <b>VIP Players</b> only). Cost - always 10kk Zen') !!}</flux:cell>
                                </flux:row>
                                <flux:row>
                                    <flux:cell>/re on</flux:cell>
                                    <flux:cell>{{ __('Turn on all requests.') }}</flux:cell>
                                </flux:row>
                                <flux:row>
                                    <flux:cell>/re off</flux:cell>
                                    <flux:cell>{{ __('Turn off all requests.') }}</flux:cell>
                                </flux:row>
                                <flux:row>
                                    <flux:cell>/re auto</flux:cell>
                                    <flux:cell>{{ __('Automatically accepts all requests.') }}</flux:cell>
                                </flux:row>
                                <flux:row>
                                    <flux:cell>/setparty &lt;password&gt;</flux:cell>
                                    <flux:cell>{{ __('Create a party that can be joined with a password.') }}</flux:cell>
                                </flux:row>
                                <flux:row>
                                    <flux:cell>/joinparty &lt;password&gt;</flux:cell>
                                    <flux:cell>{{ __('Join a party which has a password.') }}</flux:cell>
                                </flux:row>
                                <flux:row>
                                    <flux:cell>/quest</flux:cell>
                                    <flux:cell>{{ __('Shows the necessary requisites for the current quest.') }}</flux:cell>
                                </flux:row>
                                <flux:row>
                                    <flux:cell>/lock &lt;password&gt;</flux:cell>
                                    <flux:cell>{{ __('Locks your items for trade/sale.') }}</flux:cell>
                                </flux:row>
                                <flux:row>
                                    <flux:cell>/unlock &lt;password&gt;</flux:cell>
                                    <flux:cell>{{ __('Unlocks your items for trade/sale.') }}</flux:cell>
                                </flux:row>
                                <flux:row>
                                    <flux:cell>/pack &lt;type&gt; &lt;qty&gt;</flux:cell>
                                    <flux:cell>{{ __('Bundles your jewels (Bless, Soul, Life etc.).') }}</flux:cell>
                                </flux:row>
                                <flux:row>
                                    <flux:cell>/unpack &lt;type&gt; &lt;qty&gt;</flux:cell>
                                    <flux:cell>{{ __('Unbundles your bundled jewels (Bless, Soul, Life etc.).') }}</flux:cell>
                                </flux:row>
                                <flux:row>
                                    <flux:cell>/war &lt;guild name&gt;</flux:cell>
                                    <flux:cell>{{ __('Challenge another guild to a Guild War.') }}</flux:cell>
                                </flux:row>
                                <flux:row>
                                    <flux:cell>/soccer &lt;guild name&gt;</flux:cell>
                                    <flux:cell>{{ __('Challenge another guild to a Battle Soccer.') }}</flux:cell>
                                </flux:row>
                                <flux:row>
                                    <flux:cell>/king</flux:cell>
                                    <flux:cell>
                                        {{ __('Seize the throne in the') }}
                                        <flux:link href="https://wiki.lotusmu.org/events/king-of-yoskreth" external>
                                            {{ __('King of Yoskreth') }}
                                        </flux:link>
                                        {{ __('Event.') }}
                                    </flux:cell>
                                </flux:row>
                                <flux:row>
                                    <flux:cell>/join</flux:cell>
                                    <flux:cell>{{ __('Join in an Event.') }}</flux:cell>
                                </flux:row>
                                <flux:row>
                                    <flux:cell>/go</flux:cell>
                                    <flux:cell>{{ __('Join Events.') }}</flux:cell>
                                </flux:row>
                            </flux:rows>
                        </flux:table>
                    </flux:card>
                </flux:tab.panel>
            @endforeach
        </flux:tab.group>
    </div>
</flux:main>

