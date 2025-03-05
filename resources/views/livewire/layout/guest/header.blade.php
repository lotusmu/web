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

<flux:header sticky class="flex flex-col w-full !px-0">

    <livewire:layout.announcement-banner/>

    <flux:header container
                 x-data="{ isScrolled: false }"
                 x-init="
                     isScrolled = window.scrollY > 0;
                     window.addEventListener('scroll', () => isScrolled = window.scrollY > 0)
                 "
                 x-bind:class="{
                'backdrop-blur-2xl': isScrolled
             }"
                 class="w-full bg-white/95 dark:bg-transparent border-b border-zinc-200 dark:border-white/10 transition-[backdrop-filter] duration-500">

        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left"/>

        <x-brand
            :logo_light="asset('images/brand/lotusmu-logotype.svg')"
            :logo_dark="asset('images/brand/lotusmu-logotype-white.svg')"
            size="sm"
            class="max-lg:hidden"
        />

        <flux:navbar class="-mb-px max-lg:hidden">
            <flux:navbar.item :href="route('articles')" wire:navigate.hover
                              :current="request()->is('articles') || request()->is('articles/*')">
                {{ __('News') }}
            </flux:navbar.item>

            <flux:navbar.item href="{{ route('files') }}" wire:navigate.hover>
                {{ __('Files') }}
            </flux:navbar.item>

            <flux:navbar.item href="{{ route('rankings', ['tab' => 'players']) }}" wire:navigate.hover
                              :current="request()->is('rankings') || request()->is('rankings/*')">
                {{ __('Rankings') }}
            </flux:navbar.item>

            <flux:navbar.item href="{{ route('schedule') }}" wire:navigate.hover>
                {{ __('Event Schedule') }}
            </flux:navbar.item>

            <flux:separator vertical variant="subtle" class="my-2"/>

            <flux:dropdown>
                <flux:navbar.item icon-trailing="chevron-down">{{ __('Information') }}</flux:navbar.item>

                <flux:navmenu>
                    <flux:navmenu.item href="{{ route('server.overview') }}" wire:navigate.hover>
                        {{ __('Server Overview') }}
                    </flux:navmenu.item>

                    <flux:navmenu.item href="{{ route('articles', ['tab' => 'updates']) }}" wire:navigate.hover>
                        {{ __('Gameplay Updates') }}
                    </flux:navmenu.item>

                    <flux:navmenu.item href="{{ route('catalog') }}" wire:navigate.hover>
                        {{ __('Browse Offerings') }}
                    </flux:navmenu.item>

                    <flux:navmenu.item href="https://wiki.lotusmu.org" target="_blank">
                        {{ __('Wiki') }}
                    </flux:navmenu.item>
                </flux:navmenu>
            </flux:dropdown>
        </flux:navbar>

        <flux:spacer/>

        <flux:navbar class="-mb-px">
            <div class="max-sm:hidden">
                <livewire:connection-selector/>
            </div>

            <flux:button size="sm"
                         x-on:click="$flux.dark = ! $flux.dark"
                         icon="moon"
                         variant="subtle"
                         :tooltip="__('Toggle dark mode')"
                         aria-label="__('Toggle dark mode')"
                         class="max-lg:hidden"
            />

            <flux:separator variant="subtle" vertical class="mx-4 max-lg:hidden"/>

            @auth
                @if(auth()->user()->is_admin)
                    <flux:button variant="subtle" size="sm" icon="shield-exclamation" :tooltip="__('Admin Dashboard')"
                                 href="/admin" target="_blank" class="max-lg:hidden"/>
                @endif

                <flux:navbar.item icon-trailing="chevron-right" :href="route('dashboard')" wire:navigate.hover>
                    {{ __('Dashboard') }}
                </flux:navbar.item>
            @endauth

            @guest
                <flux:navbar.item icon-trailing="chevron-right" :href="route('login')" wire:navigate.hover>
                    {{ __('Sign In') }}
                </flux:navbar.item>

                <flux:button size="sm" inset="top bottom" :href="route('register')" wire:navigate.hover>
                    {{ __('Register') }}
                </flux:button>
            @endguest
        </flux:navbar>

        <flux:sidebar stashable sticky
                      class="lg:hidden border-r bg-zinc-50 dark:bg-zinc-900 border-zinc-200 dark:border-zinc-700">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark"/>

            <x-brand
                :logo_light="asset('images/brand/lotusmu-logotype.svg')"
                :logo_dark="asset('images/brand/lotusmu-logotype-white.svg')"
                size="sm"
                class="px-3"
            />

            @include('layouts.components.guest.sidebar')
        </flux:sidebar>
    </flux:header>
</flux:header>
