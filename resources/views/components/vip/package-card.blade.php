@props(['package', 'isFeatured'])


<div @class([
    'p-2 flex flex-col gap-2 rounded-2xl border border-zinc-200 dark:border-zinc-700/75 bg-zinc-100 dark:bg-zinc-900',
    'sm:col-span-2' => $isFeatured,
])>
    <div class="flex flex-col space-y-8 h-full rounded-lg shadow-sm p-6 md:p-8 bg-white dark:bg-zinc-800">
        <x-vip.package-card-header
            :tokens="$package->cost"
            :duration="$package->duration"
            :tier="$package->level->getLabel()"
            :is-best-value="$package->is_best_value"
        />

        <div @class([
            'grid gap-3',
            'sm:grid-cols-2' => $isFeatured,
        ])>
            <x-vip.benefits-list/>
        </div>

        <div>
            <flux:modal.trigger name="upgrade-to-{{ strtolower($package->level->getLabel()) }}">
                <flux:button
                    :variant="$isFeatured ? 'primary' : 'filled'"
                    icon-trailing="chevron-right"
                    class="w-full"
                >
                    Upgrade to {{ $package->level->getLabel() }}
                </flux:button>
            </flux:modal.trigger>

            <flux:modal name="upgrade-to-{{ strtolower($package->level->getLabel()) }}"
                        class="min-w-[26rem] space-y-6">
                <div>
                    <flux:heading size="lg">Upgrade to {{ $package->level->getLabel() }}?</flux:heading>

                    <flux:subheading>
                        You're about to upgrade your account to {{ $package->level->getLabel() }}.
                    </flux:subheading>
                </div>

                <div>
                    <flux:text class="flex gap-1">
                        Cost:
                        <flux:heading>{{ $package->cost }} tokens</flux:heading>
                    </flux:text>
                    <flux:text class="flex gap-1">
                        Duration:
                        <flux:heading>{{ $package->duration }} days</flux:heading>
                    </flux:text>
                </div>

                <div class="flex gap-2">
                    <flux:spacer/>

                    <flux:modal.close>
                        <flux:button variant="ghost">Cancel</flux:button>
                    </flux:modal.close>

                    <flux:button type="submit" variant="primary">Confirm</flux:button>
                </div>
            </flux:modal>
        </div>
    </div>
</div>
