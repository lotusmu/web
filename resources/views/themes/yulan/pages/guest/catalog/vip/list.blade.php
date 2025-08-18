<section class="flex w-full flex-col lg:flex-row lg:max-w-none max-w-md gap-6 lg:gap-0 mx-auto">
    @if($this->packages->isNotEmpty())
        @foreach($this->packages as $package)
            <livewire:pages.guest.catalog.vip.vip-card
                :$package
                :is-featured="$package->is_best_value"
                :wire:key="'package-' . $package->id"
            />
        @endforeach
    @endif
</section>