@props(['rows' => 6])

<div role="status" class="space-y-6 animate-pulse">
    <header>
        <flux:heading size="lg" class="w-48 bg-zinc-200 dark:bg-zinc-700 rounded-md">&nbsp;</flux:heading>
        <flux:subheading class="w-72 bg-zinc-200 dark:bg-zinc-700 rounded-md">&nbsp;</flux:subheading>
    </header>

    <flux:table>
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
                </flux:row>
            @endforeach
        </flux:rows>
    </flux:table>

    <flux:text size="sm" class="w-full bg-zinc-200 dark:bg-zinc-700 rounded-md">&nbsp;</flux:text>
</div>
