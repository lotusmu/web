@props(['rows' => 10])

<div role="status" class="overflow-x-auto relative space-y-8">
    <x-rankings.search placeholder="Search..."/>

    <flux:table class="animate-pulse">
        <flux:columns>
            <flux:column colspan="100%">
                <div class="w-full bg-zinc-200 dark:bg-zinc-700 rounded-md">&nbsp;</div>
            </flux:column>
        </flux:columns>

        <flux:rows>
            @foreach (range(1, $rows) as $i)
                <flux:row>
                    <flux:cell>
                        <div class="w-full bg-zinc-200 dark:bg-zinc-700 rounded-md">&nbsp;</div>
                    </flux:cell>

                    <flux:cell>
                        <div class="w-full bg-zinc-200 dark:bg-zinc-700 rounded-md">&nbsp;</div>
                    </flux:cell>

                    <flux:cell>
                        <div class="w-full bg-zinc-200 dark:bg-zinc-700 rounded-md">&nbsp;</div>
                    </flux:cell>

                    <flux:cell>
                        <div class="w-full bg-zinc-200 dark:bg-zinc-700 rounded-md">&nbsp;</div>
                    </flux:cell>

                    <flux:cell>
                        <div class="w-full bg-zinc-200 dark:bg-zinc-700 rounded-md">&nbsp;</div>
                    </flux:cell>

                    <flux:cell>
                        <div class="w-full bg-zinc-200 dark:bg-zinc-700 rounded-md">&nbsp;</div>
                    </flux:cell>

                    <flux:cell>
                        <div class="w-full bg-zinc-200 dark:bg-zinc-700 rounded-md">&nbsp;</div>
                    </flux:cell>

                    <flux:cell>
                        <div class="w-full bg-zinc-200 dark:bg-zinc-700 rounded-md">&nbsp;</div>
                    </flux:cell>

                    <flux:cell>
                        <div class="w-full bg-zinc-200 dark:bg-zinc-700 rounded-md">&nbsp;</div>
                    </flux:cell>

                    <flux:cell>
                        <div class="w-full bg-zinc-200 dark:bg-zinc-700 rounded-md">&nbsp;</div>
                    </flux:cell>
                </flux:row>
            @endforeach
        </flux:rows>
    </flux:table>

    <span class="sr-only">Loading...</span>
</div>
