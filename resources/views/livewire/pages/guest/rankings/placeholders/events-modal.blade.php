<div role="status" class="animate-pulse">
    <div class="mb-12">
        <flux:heading size="lg" class="w-48 bg-zinc-200 dark:bg-zinc-700 rounded-md">&nbsp;</flux:heading>
        <flux:subheading class="w-72 bg-zinc-200 dark:bg-zinc-700 rounded-md">&nbsp;</flux:subheading>
    </div>

    @foreach (range(0, 3) as $i)
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center h-12 w-12 bg-zinc-200 dark:bg-zinc-700 rounded-md">
                    <flux:icon.photo/>
                </div>

                <flux:text class="w-40 bg-zinc-200 dark:bg-zinc-700 rounded-md">&nbsp;</flux:text>
            </div>

            <flux:badge size="sm" variant="solid" class="w-16">
                &nbsp;
            </flux:badge>
        </div>

        @if(!$loop->last)
            <flux:separator variant="subtle" class="my-6"/>
        @endif
    @endforeach
    <span class="sr-only">Loading...</span>
</div>
