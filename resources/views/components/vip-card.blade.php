<div
    class="flex-1 p-2 flex flex-col gap-2 rounded-2xl border border-zinc-200 dark:border-zinc-700/75 bg-zinc-100 dark:bg-zinc-900 xl:mt-10">
    <div
        class="h-full rounded-lg shadow-sm p-6 md:p-8 flex flex-col bg-white dark:bg-zinc-800  xl:pb-12">
        <div class="mb-6 space-y-3 xl:-translate-y-px">
            <flux:badge icon="fire" inset="left" color="orange">
                Early access · 30% off
            </flux:badge>

            <div class="text-zinc-800 dark:text-white font-medium">Bronze</div>

            <div class="flex gap-2 items-baseline">
                <div class="text-3xl md:text-4xl font-semibold text-zinc-800 dark:text-white">90</div>
                <div class="text-zinc-400 dark:text-zinc-300 font-medium text-base">tokens</div>
            </div>

            <flux:subheading size="sm">3 days</flux:subheading>
        </div>

        <div class="mb-8 flex flex-col gap-3 xl:-translate-y-px">
            <x-vip.bonus-list/>
        </div>

        <flux:spacer/>

        <flux:button variant="primary" icon-trailing="chevron-right"
                     class="!text-base !h-12 xl:translate-y-px">Buy Forever
        </flux:button>
    </div>
</div>
