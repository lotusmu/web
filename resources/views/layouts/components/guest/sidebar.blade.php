<flux:navlist>
    <flux:navlist.item wire:navigate.hover icon="newspaper" :href="route('news')">
        {{ __('News') }}
    </flux:navlist.item>
    <flux:navlist.item wire:navigate.hover icon="arrow-down-tray" href="#">
        {{ __('Files') }}
    </flux:navlist.item>
    <flux:navlist.item wire:navigate.hover icon="trophy" href="#">
        {{ __('Rankings') }}
    </flux:navlist.item>
    <flux:navlist.item wire:navigate.hover icon="calendar-days" href="/upcoming-events">
        {{ __('Event Times') }}
    </flux:navlist.item>
    <flux:navlist.item wire:navigate.hover icon="information-circle" href="#">
        {{ __('Basic Information') }}
    </flux:navlist.item>
    <flux:navlist.item wire:navigate.hover icon="scroll-text" href="#">
        {{ __('Patch Notes') }}
    </flux:navlist.item>
    <flux:navlist.item wire:navigate.hover icon="book-open" href="#">
        {{ __('Wiki') }}
    </flux:navlist.item>
</flux:navlist>

<flux:spacer/>

<flux:navlist>
    @auth
        <flux:dropdown class="lg:hidden" align="end">
            <flux:navlist.item icon-trailing="chevron-down"
                               class="w-full justify-between">
                {{ auth()->user()->name }}
            </flux:navlist.item>

            <flux:navmenu>
                <flux:navmenu.item
                    x-on:click="$flux.dark = ! $flux.dark"
                    icon="moon"
                    aria-label="__('Toggle dark mode')"
                    class="lg:hidden"
                >
                    {{ __('Toggle Dark Mode') }}
                </flux:navmenu.item>

                <flux:menu.separator/>

                <flux:navmenu.item wire:navigate icon="cog-6-tooth"
                                   :href="route('profile')">
                    {{ __('Profile Settings') }}
                </flux:navmenu.item>

                <flux:navmenu.item icon="arrow-right-start-on-rectangle" wire:click="logout">
                    {{ __('Logout') }}
                </flux:navmenu.item>
            </flux:navmenu>
        </flux:dropdown>
    @endauth
</flux:navlist>
