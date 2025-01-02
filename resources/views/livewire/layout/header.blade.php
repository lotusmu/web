<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<flux:header sticky container
             x-data="{ isScrolled: false }"
             x-init="window.addEventListener('scroll', () => isScrolled = window.scrollY > 0)"
             x-bind:class="{
                'backdrop-blur-2xl': isScrolled
             }"
             class="bg-white/95 dark:bg-transparent border-b border-zinc-200 dark:border-white/10 transition-[backdrop-filter] duration-500">

    <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left"/>

    <x-brand
        :logo_light="asset('images/brand/lotusmu-logotype.svg')"
        :logo_dark="asset('images/brand/lotusmu-logotype-white.svg')"
        size="sm"
        class="max-lg:hidden"
    />

    <flux:navbar class="-mb-px max-lg:hidden">
        <flux:navbar.item :href="route('news')" wire:navigate>{{ __('News') }}</flux:navbar.item>
        <flux:navbar.item href="#" wire:navigate>{{ __('Files') }}</flux:navbar.item>
        <flux:navbar.item href="#" wire:navigate>{{ __('Rankings') }}</flux:navbar.item>
        <flux:navbar.item href="/upcoming-events">{{ __('Event Times') }}</flux:navbar.item>

        <flux:separator vertical variant="subtle" class="my-2"/>

        <flux:dropdown>
            <flux:navbar.item icon-trailing="chevron-down">{{ __('Information') }}</flux:navbar.item>

            <flux:navmenu>
                <flux:navmenu.item href="#">{{ __('Basic Information') }}</flux:navmenu.item>
                <flux:navmenu.item href="#">{{ __('Patch Notes') }}</flux:navmenu.item>
                <flux:navmenu.item href="#" wire:navigate>{{ __('Wiki') }}</flux:navmenu.item>
            </flux:navmenu>
        </flux:dropdown>
    </flux:navbar>

    <flux:spacer/>

    <flux:navbar class="-mx-px">
        <livewire:connection-selector/>

        <flux:button x-on:click="$flux.dark = ! $flux.dark"
                     icon="moon"
                     variant="subtle"
                     :tooltip="__('Toggle dark mode')"
                     aria-label="__('Toggle dark mode')"
                     class="max-lg:hidden"
        />

        <flux:dropdown>
            <flux:button icon="ellipsis-vertical" variant="subtle" inset="right" class="lg:hidden shrink-0"/>

            <flux:menu>
                <flux:menu.item :href="route('news')" wire:navigate>{{ __('News') }}</flux:menu.item>
                <flux:menu.item href="#" wire:navigate>{{ __('Files') }}</flux:menu.item>
                <flux:menu.item href="#" wire:navigate>{{ __('Rankings') }}</flux:menu.item>
                <flux:menu.item href="/upcoming-events">{{ __('Event Times') }}</flux:menu.item>

                <flux:menu.submenu :heading="__('Information')">
                    <flux:menu.item href="#">{{ __('Basic Information') }}</flux:menu.item>
                    <flux:menu.item href="#">{{ __('Patch Notes') }}</flux:menu.item>
                    <flux:menu.item href="https://wiki.lotusmu.org" target="_blank">{{ __('Wiki') }}</flux:menu.item>
                </flux:menu.submenu>
            </flux:menu>
        </flux:dropdown>

        <flux:separator variant="subtle" vertical class="mx-4 max-lg:hidden"/>

        <flux:button variant="subtle" size="sm" icon="shield-exclamation" :tooltip="__('Admin Dashboard')"
                     href="/admin" target="_blank" class="max-lg:hidden"/>

        @if(false)
            <div class="relative">
                <flux:button variant="ghost" size="sm" icon="bell"/>
                <div
                    class="absolute top-0 right-0 bg-red-600 text-white text-[11px] text-center min-w-4 min-h-4 rounded-full"
                >
                    1
                </div>
            </div>
        @endif

        <flux:dropdown class="max-lg:hidden">
            <flux:navbar.item icon-trailing="chevron-down">
                {{ auth()->user()->name }}
            </flux:navbar.item>

            <flux:navmenu>
                <flux:navmenu.item
                    wire:navigate
                    icon="cog-6-tooth"
                    :href="route('profile')"
                >
                    {{ __('Profile Settings') }}
                </flux:navmenu.item>

                <flux:navmenu.item
                    wire:click="logout"
                    icon="arrow-right-start-on-rectangle"
                >
                    {{ __('Logout') }}
                </flux:navmenu.item>
            </flux:navmenu>
        </flux:dropdown>
    </flux:navbar>
</flux:header>
