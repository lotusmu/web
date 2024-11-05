<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {
    public function extend(): void
    {
        $this->modal('extend')->close();

        Flux::toast('extended!');
    }

    public function enable(): void
    {
        $this->modal('enable')->close();

        Flux::toast('enabled!');
    }
}; ?>

<div class="space-y-6">
    <header>
        <flux:heading size="xl">
            {{ __('Stealth Mode') }}
        </flux:heading>

        <x-flux::subheading>
            {{ __('Stealth Mode conceals your character\'s information from public view.') }}
        </x-flux::subheading>
    </header>


    <div class="flex max-md:flex-col gap-6 w-full">
        <flux:card class="flex-1 space-y-6">
            <flux:heading class="flex items-center gap-2">
                <flux:icon.eye/>
                <span>{{__('Normal Mode')}}</span>
            </flux:heading>

            <div class="space-y-2">
                <div class="flex gap-2 items-center">
                    <flux:icon.x-mark variant="mini" class="text-red-500 dark:text-red-400"/>
                    <flux:subheading>
                        {{ __('Character stats are visible to everyone') }}
                    </flux:subheading>
                </div>

                <div class="flex gap-2 items-center">
                    <flux:icon.x-mark variant="mini" class="text-red-500 dark:text-red-400"/>
                    <flux:subheading>
                        {{ __('Players can track your location') }}
                    </flux:subheading>
                </div>

                <div class="flex gap-2 items-center">
                    <flux:icon.x-mark variant="mini" class="text-red-500 dark:text-red-400"/>
                    <flux:subheading>
                        {{ __('Account information is public') }}
                    </flux:subheading>
                </div>
            </div>
        </flux:card>

        <flux:card class="flex-1 space-y-6 bg-zinc-800 dark:!bg-white !border-zinc-950 dark:!border-white">
            <flux:heading class="flex items-center gap-2 !text-white dark:!text-zinc-800">
                <flux:icon.eye-slash/>
                <span>{{__('Stealth Mode')}}</span>
            </flux:heading>


            <div class="space-y-2">
                <div class="flex gap-2 items-center">
                    <flux:icon.check variant="mini" class="dark:text-emerald-500 text-emerald-400"/>
                    <flux:subheading class="dark:!text-zinc-500 !text-white/70">
                        {{ __('Keep your character stats private') }}
                    </flux:subheading>
                </div>

                <div class="flex gap-2 items-center">
                    <flux:icon.check variant="mini" class="dark:text-emerald-500 text-emerald-400"/>
                    <flux:subheading class="dark:!text-zinc-500 !text-white/70">
                        {{ __('Hide your location from other players') }}
                    </flux:subheading>
                </div>

                <div class="flex gap-2 items-center">
                    <flux:icon.check variant="mini" class="dark:text-emerald-500 text-emerald-400"/>
                    <flux:subheading class="dark:!text-zinc-500 !text-white/70">
                        {{ __('Full account information privacy') }}
                    </flux:subheading>
                </div>
            </div>
        </flux:card>
    </div>

    {{-- ACTIVATE --}}
    {{--    <flux:card class="space-y-6">--}}
    {{--        <div class="flex max-sm:flex-col max-sm:space-y-2 items-start w-full">--}}
    {{--            <div class="space-y-4">--}}
    {{--                <flux:heading class="flex items-center gap-2">--}}
    {{--                    <flux:icon.eye-slash/>--}}
    {{--                    <span>{{__('Stealth Mode')}}</span>--}}
    {{--                </flux:heading>--}}

    {{--                <div class="flex gap-2 items-baseline">--}}
    {{--                    <div--}}
    {{--                        class="flex items-center gap-2 text-3xl md:text-4xl font-semibold text-zinc-800 dark:text-white">--}}
    {{--                        60--}}
    {{--                    </div>--}}
    {{--                    <div class="text-zinc-400 dark:text-zinc-300 font-medium text-base">{{ __('tokens') }}</div>--}}
    {{--                </div>--}}
    {{--            </div>--}}

    {{--            <flux:spacer/>--}}

    {{--            <flux:badge variant="pill" icon="calendar-days" color="orange">--}}
    {{--                {{ __('7 days') }}--}}
    {{--            </flux:badge>--}}
    {{--        </div>--}}

    {{--        <div>--}}
    {{--            <flux:modal.trigger name="enable">--}}
    {{--                <flux:button variant="primary" icon-trailing="chevron-right" class="w-full">--}}
    {{--                    {{__('Enable')}}--}}
    {{--                </flux:button>--}}
    {{--            </flux:modal.trigger>--}}
    {{--        </div>--}}

    {{--        <flux:modal name="enable"--}}
    {{--                    class="min-w-[26rem] space-y-6">--}}
    {{--            <div>--}}
    {{--                <flux:heading size="lg">Enable Stealth Mode?</flux:heading>--}}

    {{--                <flux:subheading>--}}
    {{--                    Your account information will be hidden for 7 days.--}}
    {{--                </flux:subheading>--}}
    {{--            </div>--}}

    {{--            <div>--}}
    {{--                <flux:text class="flex gap-1">--}}
    {{--                    Price:--}}
    {{--                    <flux:heading>60 tokens</flux:heading>--}}
    {{--                </flux:text>--}}
    {{--                <flux:text class="flex gap-1">--}}
    {{--                    Period:--}}
    {{--                    <flux:heading>7 days</flux:heading>--}}
    {{--                </flux:text>--}}
    {{--            </div>--}}

    {{--            <div class="flex gap-2">--}}
    {{--                <flux:spacer/>--}}

    {{--                <flux:modal.close>--}}
    {{--                    <flux:button variant="ghost">Cancel</flux:button>--}}
    {{--                </flux:modal.close>--}}

    {{--                <flux:button type="button" variant="primary" wire:click="enable">--}}
    {{--                    Enable--}}
    {{--                </flux:button>--}}
    {{--            </div>--}}
    {{--        </flux:modal>--}}
    {{--    </flux:card>--}}


    {{--    EXTEND --}}
    <flux:card class="space-y-6">
        <div class="flex max-sm:flex-col max-sm:space-y-2 items-start w-full">
            <div class="space-y-4">
                <flux:heading class="flex items-center gap-2">
                    <flux:icon.eye-slash/>
                    <span>{{__('Stealth Mode')}}</span>
                </flux:heading>

                <div class="flex gap-2 items-baseline">
                    <div
                        class="flex items-center gap-2 text-3xl md:text-4xl font-semibold text-zinc-800 dark:text-white">
                        60
                    </div>
                    <div class="text-zinc-400 dark:text-zinc-300 font-medium text-base">{{ __('tokens') }}</div>
                </div>
            </div>

            <flux:spacer/>

            <flux:badge variant="pill" icon="calendar-days" color="green">
                {{ __('Active until Nov 12 2024, 17:12') }}
            </flux:badge>
        </div>

        <div>
            <flux:modal.trigger name="extend">
                <flux:button variant="primary" icon-trailing="chevron-right" class="w-full">
                    {{__('Extend 7 Days')}}
                </flux:button>
            </flux:modal.trigger>
        </div>

        <flux:modal name="extend"
                    class="min-w-[26rem] space-y-6">
            <div>
                <flux:heading size="lg">Extend Stealth Mode</flux:heading>

                <flux:subheading>
                    Your stealth mode period will be extended for 7 more days.
                </flux:subheading>
            </div>

            <div>
                <flux:text class="flex gap-1">
                    Price:
                    <flux:heading>60 tokens</flux:heading>
                </flux:text>
                <flux:text class="flex gap-1">
                    Period:
                    <flux:heading>7 days</flux:heading>
                </flux:text>
            </div>

            <div class="flex gap-2">
                <flux:spacer/>

                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>

                <flux:button type="button" variant="primary" wire:click="extend">
                    Extend
                </flux:button>
            </div>
        </flux:modal>
    </flux:card>
</div>
