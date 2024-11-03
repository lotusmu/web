<flux:navlist>
    <flux:navlist.item wire:navigate icon="home" href="/dashboard">
        {{ __('Dashboard') }}
    </flux:navlist.item>
    <flux:navlist.item wire:navigate icon="wallet" href="/wallet">
        {{ __('Wallet') }}
    </flux:navlist.item>
    <flux:navlist.item wire:navigate icon="clock" href="/entries">
        {{ __('Event Entries') }}
    </flux:navlist.item>
    <flux:navlist.item wire:navigate icon="building-library" href="#">
        {{ __('Castle Siege') }}
    </flux:navlist.item>
    <flux:navlist.item wire:navigate icon="fire" href="/vip"
                       :current="request()->is('vip') || request()->is('vip/*')">
        {{ __('Account Level') }}
    </flux:navlist.item>
    <flux:navlist.item wire:navigate icon="eye-slash" href="#">
        {{ __('Hide Info') }}
    </flux:navlist.item>
    <flux:navlist.item wire:navigate icon="sparkles" href="#">
        {{ __('Donate') }}
    </flux:navlist.item>
    <flux:navlist.item wire:navigate icon="hand-thumb-up" href="#">
        {{ __('Vote') }}
    </flux:navlist.item>
    <flux:navlist.item wire:navigate icon="list-bullet" href="/activities">
        {{ __('Activities') }}
    </flux:navlist.item>

    <flux:separator variant="subtle" class="my-px"/>

    <flux:navlist.item wire:navigate
                       icon="chat-bubble-left-ellipsis"
                       href="/support"
                       :current="request()->is('support') || request()->is('support/*')">
        {{ __('Support') }}
    </flux:navlist.item>
</flux:navlist>
