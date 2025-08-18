<div @class([
    'flex-1 p-2 flex flex-col rounded-2xl bg-zinc-100 dark:bg-zinc-900',
    'border border-zinc-200 dark:border-zinc-700/75 lg:mt-10 lg:pr-0 lg:border-r-0 lg:rounded-r-none' => !$isFeatured && $package->catalog_order === 1, // Left card
    'border-2 border-zinc-800 dark:border-zinc-200 lg:-mb-4' => $isFeatured, // Middle card
    'border border-zinc-200 dark:border-zinc-700/75 lg:mt-10 lg:pl-0 lg:border-l-0 lg:rounded-l-none' => !$isFeatured && $package->catalog_order === 3, // Right card
])>
    <div @class([
        'space-y-8 h-full rounded-lg shadow-sm p-6 md:p-8 flex flex-col bg-white dark:bg-zinc-800',
        'lg:rounded-r-none' => !$isFeatured && $package->catalog_order === 1, // Left Card
        'lg:pb-12' => $isFeatured, // Middle card
        'lg:rounded-l-none' => !$isFeatured && $package->catalog_order === 3, // Right Card
    ])>
        <x-vip.card-header
            :tokens="$package->cost"
            :duration="$package->duration"
            :tier="$this->label"
            :is-best-value="$package->is_best_value"
        />

        <div class="grid gap-3">
            <x-vip.benefits-list/>
        </div>

        <flux:spacer/>

        <flux:button
            :variant="$isFeatured ? 'primary' : 'filled'"
            icon-trailing="chevron-right"
            class="w-full"
            wire:click="upgrade"
        >
            {{__('Upgrade to')}} {{ $this->label }}
        </flux:button>
    </div>

    <flux:modal name="upgrade-to-{{ strtolower($this->label) }}"
                class="min-w-[26rem] space-y-6">
        <div>
            <flux:heading size="lg">{{__('Upgrade to')}} {{ $this->label }}</flux:heading>
            <flux:subheading>
                {{__('You\'re about to upgrade your account to')}} {{ $this->label }}.
            </flux:subheading>
        </div>

        <div>
            <flux:text class="flex gap-1">
                {{__('Price')}}:
                <flux:heading>{{ $package->cost }} {{__('tokens')}}</flux:heading>
            </flux:text>
            <flux:text class="flex gap-1">
                {{__('Period')}}:
                <flux:heading>{{ $package->duration }} {{__('days')}}</flux:heading>
            </flux:text>
        </div>

        <div class="flex gap-2">
            <flux:spacer/>

            <flux:modal.close>
                <flux:button variant="ghost">
                    {{__('Cancel')}}
                </flux:button>
            </flux:modal.close>

            <flux:button type="button" variant="primary"
                         wire:click="$parent.purchase({{ $package->id }})">
                {{__('Upgrade')}}
            </flux:button>
        </div>
    </flux:modal>
</div>
