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

<flux:header class="!block bg-white lg:bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700">
    <flux:navbar class="lg:hidden w-full">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left"/>

        <flux:spacer/>

        <flux:navbar.item href="/admin">Admin Dashboard</flux:navbar.item>
        <flux:button variant="ghost" size="sm" icon="moon" tooltip="Toggle dark mode"
                     x-on:click="$store.darkMode.toggle()"/>

        <flux:separator variant="subtle" vertical class="mx-4"/>

        <flux:dropdown>
            <flux:button variant="ghost" size="sm"
                         icon-trailing="chevron-down">{{ auth()->user()->name }}</flux:button>

            <flux:menu>
                <livewire:logout/>
            </flux:menu>
        </flux:dropdown>
    </flux:navbar>

    <flux:navbar scrollable>
        <flux:navbar.item href="#" wire:navigate>News</flux:navbar.item>
        <flux:navbar.item href="#" wire:navigate>Files</flux:navbar.item>
        <flux:navbar.item href="#" wire:navigate>Rankings</flux:navbar.item>
        <flux:navbar.item href="/upcoming-events">Event Times</flux:navbar.item>

        <flux:separator vertical variant="subtle" class="my-2"/>

        <flux:dropdown class="max-lg:hidden">
            <flux:navbar.item icon-trailing="chevron-down">Information</flux:navbar.item>

            <flux:navmenu>
                <flux:navmenu.item href="#">Basic Information</flux:navmenu.item>
                <flux:navmenu.item href="#">Patch Notes</flux:navmenu.item>
                <flux:navmenu.item href="#" wire:navigate>Wiki</flux:navmenu.item>
            </flux:navmenu>
        </flux:dropdown>

        <flux:spacer/>

        <div class="flex items-center gap-1 max-lg:hidden">
            <flux:navbar.item href="/admin">Admin Dashboard</flux:navbar.item>
            <flux:button variant="ghost" size="sm" icon="moon" tooltip="Toggle dark mode"
                         x-on:click="$store.darkMode.toggle()"/>


            <flux:separator vertical variant="subtle" class="my-2 mx-4"/>


            <flux:dropdown>
                <flux:button variant="ghost" size="sm"
                             icon-trailing="chevron-down">{{ auth()->user()->name }}</flux:button>

                <flux:menu>
                    <flux:menu.item icon="arrow-right-start-on-rectangle" wire:click="logout">Logout
                    </flux:menu.item>
                </flux:menu>
            </flux:dropdown>
        </div>
    </flux:navbar>
</flux:header>
