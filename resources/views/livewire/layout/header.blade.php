<?php

use App\Livewire\Actions\Logout;
use App\Models\Concerns\GameConnectionSelector;
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

<flux:header sticky container class="bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700">
    <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left"/>

    <x-brand size="sm" logo="{{asset('images/logo/logo-hor-light.svg')}}" class="max-lg:hidden dark:hidden"/>
    <x-brand size="sm" logo="{{asset('images/logo/logo-hor-dark.svg')}}" class="max-lg:!hidden hidden dark:flex"/>

    <flux:navbar class="-mb-px max-lg:hidden">
        <flux:navbar.item href="#" wire:navigate>News</flux:navbar.item>
        <flux:navbar.item href="#" wire:navigate>Files</flux:navbar.item>
        <flux:navbar.item href="#" wire:navigate>Rankings</flux:navbar.item>
        <flux:navbar.item href="/upcoming-events">Event Times</flux:navbar.item>

        <flux:separator vertical variant="subtle" class="my-2"/>

        <flux:dropdown>
            <flux:navbar.item icon-trailing="chevron-down">Information</flux:navbar.item>

            <flux:navmenu>
                <flux:navmenu.item href="#">Basic Information</flux:navmenu.item>
                <flux:navmenu.item href="#">Patch Notes</flux:navmenu.item>
                <flux:navmenu.item href="#" wire:navigate>Wiki</flux:navmenu.item>
            </flux:navmenu>
        </flux:dropdown>
    </flux:navbar>

    <flux:spacer/>

    <flux:navbar class="mr-4">
        <livewire:connection-switch/>

        <flux:button variant="ghost" size="sm" icon="moon" tooltip="Toggle dark mode"
                     x-on:click="$store.darkMode.toggle()" class="max-lg:hidden"/>

        <flux:dropdown>
            <flux:button icon="ellipsis-vertical" variant="ghost" class="lg:hidden"/>

            <flux:menu>
                <flux:menu.item href="#" wire:navigate>News</flux:menu.item>
                <flux:menu.item href="#" wire:navigate>Files</flux:menu.item>
                <flux:menu.item href="#" wire:navigate>Rankings</flux:menu.item>
                <flux:menu.item href="/upcoming-events">Event Times</flux:menu.item>

                <flux:menu.submenu heading="Information">
                    <flux:menu.item href="#">Basic Information</flux:menu.item>
                    <flux:menu.item href="#">Patch Notes</flux:menu.item>
                    <flux:menu.item href="https://wiki.lotusmu.org" target="_blank">Wiki</flux:menu.item>
                </flux:menu.submenu>

            </flux:menu>
        </flux:dropdown>

        <flux:separator variant="subtle" vertical class="mx-4"/>

        <flux:dropdown>
            <flux:button variant="ghost" size="sm" inset="right" icon-trailing="chevron-down">
                {{ auth()->user()->name }}
            </flux:button>

            <flux:navmenu>
                <flux:navmenu.item icon="moon" x-on:click="$store.darkMode.toggle()" class="lg:hidden">
                    Toggle Dark Mode
                </flux:navmenu.item>

                <flux:navmenu.item icon="squares-2x2" href="/admin">Admin Dashboard</flux:navmenu.item>
                <flux:navmenu.item wire:navigate icon="cog-6-tooth" href="/profile">Settings</flux:navmenu.item>
                <flux:navmenu.item icon="arrow-right-start-on-rectangle" wire:click="logout">
                    Logout
                </flux:navmenu.item>
            </flux:navmenu>
        </flux:dropdown>
    </flux:navbar>
</flux:header>
