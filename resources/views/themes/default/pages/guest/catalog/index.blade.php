<flux:main container class="max-sm:space-y-24 space-y-40 mb-20">
    <x-page-header
        :title="__('Your path, your possibilities')"
        :kicker="__('Catalog')"
        :description="__('Explore our selection of convenience features and enhancements designed to complement your gameplay experience. See detailed information about everything available in the game.')"
    />

    <livewire:pages.guest.catalog.vip.vip-list/>

    <!-- Buffs -->
    <livewire:pages.guest.catalog.buffs lazy/>

    <!-- Item Bundles -->
    <livewire:pages.guest.catalog.packs lazy/>

    <!-- Consumables -->
    <livewire:pages.guest.catalog.supplies lazy/>
</flux:main>