<flux:navlist>
    <flux:navlist.item wire:navigate.hover icon="home" :href="route('dashboard')">
        {{ __('Home') }}
    </flux:navlist.item>
    <flux:navlist.item wire:navigate.hover icon="wallet" :href="route('wallet')">
        {{ __('Wallet') }}
    </flux:navlist.item>
    <flux:navlist.item wire:navigate.hover icon="timer" :href="route('entries')">
        {{ __('Event Entries') }}
    </flux:navlist.item>
    <flux:navlist.item wire:navigate.hover icon="castle" :href="route('castle')">
        {{ __('Castle Siege') }}
    </flux:navlist.item>
    <flux:navlist.item wire:navigate.hover icon="fire" :href="route('vip')"
                       :current="request()->is('vip') || request()->is('vip/*')">
        {{ __('Account Level') }}
    </flux:navlist.item>
    <flux:navlist.item wire:navigate.hover icon="eye-slash" :href="route('stealth')">
        {{ __('Stealth Mode') }}
    </flux:navlist.item>
    <flux:navlist.item wire:navigate.hover icon="hand-coins" :href="route('donate')">
        {{ __('Donate') }}
    </flux:navlist.item>
    <flux:navlist.item wire:navigate.hover icon="scroll-text" :href="route('activities')">
        {{ __('Activities') }}
    </flux:navlist.item>

    <flux:separator variant="subtle" class="my-px"/>

    <flux:navlist.item wire:navigate.hover
                       icon="envelope-open"
                       :href="route('support')"
                       :current="request()->is('support') || request()->is('support/*')">
        {{ __('Support') }}
    </flux:navlist.item>
</flux:navlist>

<flux:spacer/>

<flux:navlist>
    <flux:dropdown class="lg:hidden" align="end">
        <flux:navlist.item icon-trailing="chevron-down"
                           class="w-full justify-between">
            {{ auth()->user()->name }}
        </flux:navlist.item>

        <flux:navmenu>
            <flux:radio.group x-data variant="segmented" x-model="$flux.appearance">
                <flux:radio value="light" icon="sun"/>
                <flux:radio value="dark" icon="moon"/>
                <flux:radio value="system" icon="computer-desktop"/>
            </flux:radio.group>

            <flux:menu.separator/>

            <flux:navmenu.item icon="shield-exclamation" href="/admin" target="_blank" class="lg:hidden">
                {{ __('Admin Dashboard') }}
            </flux:navmenu.item>

            <flux:navmenu.item wire:navigate icon="cog-6-tooth"
                               :href="route('profile')">
                {{ __('Profile Settings') }}
            </flux:navmenu.item>

            <flux:navmenu.item icon="arrow-right-start-on-rectangle" wire:click="logout">
                {{ __('Logout') }}
            </flux:navmenu.item>
        </flux:navmenu>
    </flux:dropdown>
</flux:navlist>
