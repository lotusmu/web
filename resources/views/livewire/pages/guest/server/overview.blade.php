<?php

use App\Models\Content\Download;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
//
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

        <flux:card class="space-y-6">
            <flux:heading size="lg">
                {{ __('General Information') }}
            </flux:heading>

            <flux:table>
                <flux:rows>
                    <flux:row>
                        <flux:cell>{{ __('Server Version') }}</flux:cell>
                        <flux:cell>Season 3</flux:cell>
                    </flux:row>
                    <flux:row>
                        <flux:cell>{{ __('Available Character Classes') }}</flux:cell>
                        <flux:cell>DK, DW, FE, MG, DL</flux:cell>
                    </flux:row>
                    <flux:row>
                        <flux:cell>{{ __('Experience') }}</flux:cell>
                        <flux:cell>
                            <flux:link href="https://wiki.lotusmu.org/gameplay-systems/reset-system/">
                                {{ __('Dynamic [x10 start]') }}
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
                            <flux:link href="https://wiki.lotusmu.org/gameplay-systems/reset-system/">
                                {{ __('30 [10 start]') }}
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
                        <flux:cell>{{ __('20kk x Reset Number') }}</flux:cell>
                    </flux:row>
                    <flux:row>
                        <flux:cell>{{ __('Clear PK Zen') }}</flux:cell>
                        <flux:cell>{{ __('5kk x Number of Kills') }}</flux:cell>
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
    </div>
</flux:main>

