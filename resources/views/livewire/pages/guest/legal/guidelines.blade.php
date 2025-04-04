<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    //
}; ?>

<flux:main container>
    <div class="pt-16 mx-auto max-w-3xl space-y-12">
        <div>
            <flux:heading size="xl">
                {{ __('Community Guidelines') }}
            </flux:heading>
            <flux:subheading>
                {{ __('Official rules and conduct standards for Lotus Mu') }}
            </flux:subheading>
            <flux:text size="sm" class="mt-2">
                {{ __('Last Updated: January 2025') }}
            </flux:text>
        </div>

        <div class="space-y-2">
            <flux:heading size="lg">
                {{ __('Server Regulations') }}
            </flux:heading>

            <flux:text>
                {{ __('All users must comply with these regulations:') }}
            </flux:text>

            <flux:text>
                <ul class="list-disc pl-6 space-y-2">
                    <li>{{ __('All accounts, characters, and game items are intellectual property of Lotus Mu') }}</li>
                    <li>{{ __('The server administration guarantees account security for compliant users') }}</li>
                    <li>{{ __('False information or testimonies will result in warnings or bans') }}</li>
                    <li>{{ __('Users must report all violations to administration') }}</li>
                </ul>
            </flux:text>
        </div>

        <div class="space-y-2">
            <flux:heading size="lg">
                {{ __('Staff Relations') }}
            </flux:heading>

            <flux:text>
                <ul class="list-disc pl-6 space-y-2">
                    <li>
                        {{ __('Disrespectful conduct towards server staff is prohibited') }}
                        <flux:text size="sm">{{ __('Penalty: 7-day account ban') }}</flux:text>
                    </li>
                    <li>
                        {{ __('Making threats against server staff is forbidden') }}
                        <flux:text size="sm">{{ __('Penalty: 7-day account ban') }}</flux:text>
                    </li>
                    <li>
                        {{ __("Disrespectful conduct towards server staff, making threats, or attempting to undermine the authority of the server is not tolerated") }}
                        <flux:text size="sm">{{ __('Penalty: Chat ban or 7-day account block') }}</flux:text>
                    </li>
                </ul>
            </flux:text>
        </div>

        <div class="space-y-2">
            <flux:heading size="lg">
                {{ __('Technical Violations') }}
            </flux:heading>

            <flux:text>
                <ul class="list-disc pl-6 space-y-2">
                    <li>
                        {{ __('Exploiting glitches or using unauthorized programs is prohibited') }}
                        <flux:text size="sm">{{ __('Penalty: 7-day to permanent account ban') }}</flux:text>
                    </li>
                    <li>
                        {{ __('All bugs must be reported to administration') }}
                        <flux:text
                            size="sm">{{ __('Penalty: 7-day to permanent account ban if exploited') }}</flux:text>
                    </li>
                </ul>
            </flux:text>
        </div>

        <div class="space-y-2">
            <flux:heading size="lg">
                {{ __('Gameplay Rules') }}
            </flux:heading>

            <flux:text>
                <ul class="list-disc pl-6 space-y-2">
                    <li>
                        {{ __('Inciting ethnic hatred or player harassment') }}
                        <flux:text size="sm">{{ __('Penalty: Chat ban or 7-day account block') }}</flux:text>
                    </li>
                    <li>
                        {{ __('Advertising other game servers') }}
                        <flux:text size="sm">{{ __('Penalty: 30-day to permanent account ban') }}</flux:text>
                    </li>
                    <li>
                        {{ __('Event manipulation or inactivity') }}
                        <flux:text size="sm">{{ __('Penalty: 3-day account ban') }}</flux:text>
                    </li>
                    <li>
                        {{ __('Castle Siege manipulation via dummy guilds') }}
                        <flux:text size="sm">{{ __('Penalty: 14-day account ban') }}</flux:text>
                    </li>
                    <li>
                        {{ __('Stealing event rewards') }}
                        <flux:text size="sm">{{ __('Penalty: 3-day character ban') }}</flux:text>
                    </li>
                    <li>
                        {{ __('Creating fake evidence or misleading administration') }}
                        <flux:text size="sm">{{ __('Penalty: 30-day account ban') }}</flux:text>
                    </li>
                </ul>
            </flux:text>
        </div>

        <div class="space-y-2">
            <flux:heading size="lg">
                {{ __('Additional Notes') }}
            </flux:heading>

            <flux:text>
                {{ __('Please be aware of the following:') }}
            </flux:text>

            <flux:text>
                <ul class="list-disc pl-6 space-y-2">
                    <li>{{ __('Punishment duration increases with repeated violations') }}</li>
                    <li>{{ __('Game Masters may make decisions based on context beyond these rules') }}</li>
                    <li>{{ __('The administration reserves the right to modify ban durations') }}</li>
                </ul>
            </flux:text>
        </div>

        <div class="space-y-2">
            <flux:heading size="lg">
                {{ __('Contact Information') }}
            </flux:heading>

            <flux:text>
                {{ __('For questions about these guidelines or to report violations, you can:') }}
            </flux:text>

            <flux:text>
                <ul class="list-disc pl-6 space-y-2">
                    <li>
                        {{ __('Email us at') }}
                        <flux:link href="mailto:support@lotusmu.org">support@lotusmu.org</flux:link>
                    </li>
                    <li>
                        {{ __('Join our') }}
                        <flux:link href="{{ config('social.links.discord') }}">{{ __('Discord Server') }}</flux:link>
                        {{ __('for real-time support') }}
                    </li>
                    <li>
                        {{ __('Visit our') }}
                        <flux:link href="{{ route('support') }}" wire:navigate>{{ __('Help Center') }}</flux:link>
                    </li>
                </ul>
            </flux:text>
        </div>
    </div>
</flux:main>
