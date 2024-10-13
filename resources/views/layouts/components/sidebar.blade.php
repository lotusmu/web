<flux:navlist>
    <flux:navlist.item wire:navigate icon="home" href="/dashboard">
        Dashboard
    </flux:navlist.item>
    <flux:navlist.item wire:navigate icon="wallet" href="/wallet">
        Wallet
    </flux:navlist.item>
    <flux:navlist.item wire:navigate icon="clock" href="/entries">
        Event Entries
    </flux:navlist.item>
    <flux:navlist.item wire:navigate icon="building-library" href="#">
        Castle Siege
    </flux:navlist.item>
    <flux:navlist.item wire:navigate icon="fire" href="/vip">
        Buy VIP
    </flux:navlist.item>
    <flux:navlist.item wire:navigate icon="eye-slash" href="#">
        Hide Info
    </flux:navlist.item>
    <flux:navlist.item wire:navigate icon="arrows-pointing-out" href="#">
        Unstuck Character
    </flux:navlist.item>
    <flux:navlist.item wire:navigate icon="sparkles" href="#">
        Donate
    </flux:navlist.item>
    <flux:navlist.item wire:navigate icon="hand-thumb-up" href="#">
        Vote
    </flux:navlist.item>
    <flux:navlist.item wire:navigate icon="list-bullet" href="/activities">
        Activities
    </flux:navlist.item>

    <flux:separator variant="subtle" class="my-px"/>

    <flux:navlist.item wire:navigate
                       icon="chat-bubble-left-ellipsis"
                       href="/support"
                       :current="request()->is('support') || request()->is('support/*')">
        Support
    </flux:navlist.item>
</flux:navlist>
