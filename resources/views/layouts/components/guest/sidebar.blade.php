<flux:navlist>
    <div class="sm:hidden mb-8">
        <livewire:connection-selector triggerType="navlist"/>
    </div>

    <flux:navlist.item wire:navigate.hover icon="newspaper"
                       :href="route('articles')"
                       :current="request()->is('articles') || request()->is('articles/*')">
        {{ __('News') }}
    </flux:navlist.item>

    <flux:navlist.item wire:navigate.hover icon="cloud-arrow-down" href="{{ route('files') }}">
        {{ __('Files') }}
    </flux:navlist.item>

    <flux:navlist.item wire:navigate.hover icon="trophy"
                       href="{{ route('rankings', ['tab' => 'players']) }}"
                       :current="request()->is('rankings') || request()->is('rankings/*')">
        {{ __('Rankings') }}
    </flux:navlist.item>

    <flux:navlist.item wire:navigate.hover icon="calendar-days" href="{{ route('schedule') }}">
        {{ __('Event Schedule') }}
    </flux:navlist.item>

    <flux:navlist.item wire:navigate.hover icon="information-circle" href="{{ route('server.overview') }}">
        {{ __('Server Overview') }}
    </flux:navlist.item>

    <flux:navlist.item wire:navigate.hover icon="scroll-text"
                       href="{{ route('articles', ['tab' => 'updates']) }}">
        {{ __('Gameplay Updates') }}
    </flux:navlist.item>

    <flux:navlist.item wire:navigate.hover icon="building-storefront"
                       href="{{ route('catalog') }}">
        {{ __('Browse Offerings') }}
    </flux:navlist.item>

    <flux:navlist.item icon="book-open" href="https://wiki.lotusmu.org" target="_blank">
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
                <flux:radio.group x-data variant="segmented" x-model="$flux.appearance">
                    <flux:radio value="light" icon="sun"/>
                    <flux:radio value="dark" icon="moon"/>
                    <flux:radio value="system" icon="computer-desktop"/>
                </flux:radio.group>

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
