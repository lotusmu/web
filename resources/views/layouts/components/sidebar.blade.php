<flux:navlist>
    <flux:navlist.item wire:navigate icon="home" href="/dashboard">
        {{ __('Dashboard') }}
    </flux:navlist.item>
    <flux:navlist.item wire:navigate icon="wallet" href="/wallet">
        {{ __('Wallet') }}
    </flux:navlist.item>
    <flux:navlist.item wire:navigate icon="timer" href="/entries">
        {{ __('Event Entries') }}
    </flux:navlist.item>
    <flux:navlist.item wire:navigate icon="castle" href="#">
        {{ __('Castle Siege') }}
    </flux:navlist.item>
    <flux:navlist.item wire:navigate icon="fire" href="/vip"
                       :current="request()->is('vip') || request()->is('vip/*')">
        {{ __('Account Level') }}
    </flux:navlist.item>
    <flux:navlist.item wire:navigate icon="eye-slash" href="/stealth">
        {{ __('Stealth Mode') }}
    </flux:navlist.item>
    <flux:navlist.item wire:navigate icon="hand-coins" href="#">
        {{ __('Donate') }}
    </flux:navlist.item>
    <flux:navlist.item wire:navigate icon="vote" href="#">
        {{ __('Vote') }}
    </flux:navlist.item>
    <flux:navlist.item wire:navigate icon="scroll-text" href="/activities">
        {{ __('Activities') }}
    </flux:navlist.item>

    <flux:separator variant="subtle" class="my-px"/>

    <flux:navlist.item wire:navigate
                       icon="envelope-open"
                       href="/support"
                       :current="request()->is('support') || request()->is('support/*')">
        {{ __('Support') }}
    </flux:navlist.item>
</flux:navlist>
