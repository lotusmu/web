@php use App\Enums\Utility\FilterCharacterClass; @endphp
@props(['rows' => 10, 'filters' => true])

<div role="status" class="overflow-x-auto relative space-y-8">
    @if($filters)
        <div>
            <flux:radio.group variant="cards" class="lg:flex hidden items-center justify-center">
                @foreach(range(1, count(FilterCharacterClass::getAvailableCharacters())) as $i)
                    <flux:radio class="flex flex-col items-center justify-center !gap-2 !flex-none min-w-28">
                        <div class="w-12 h-12 bg-zinc-200 dark:bg-zinc-700 rounded-xl">&nbsp;</div>
                        <div class="w-16 h-4 bg-zinc-200 dark:bg-zinc-700 rounded-md">&nbsp;</div>
                    </flux:radio>
                @endforeach
            </flux:radio.group>

            <div class="lg:hidden">
                <div class="h-10 bg-zinc-200 dark:bg-zinc-700 rounded-lg">&nbsp;</div>
            </div>
        </div>
    @endif

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

                    <flux:cell class="max-sm:hidden">
                        <div class="w-full bg-zinc-200 dark:bg-zinc-700 rounded-md">&nbsp;</div>
                    </flux:cell>

                    <flux:cell class="max-sm:hidden">
                        <div class="w-full bg-zinc-200 dark:bg-zinc-700 rounded-md">&nbsp;</div>
                    </flux:cell>

                    <flux:cell class="max-sm:hidden">
                        <div class="w-full bg-zinc-200 dark:bg-zinc-700 rounded-md">&nbsp;</div>
                    </flux:cell>

                    <flux:cell class="max-sm:hidden">
                        <div class="w-full bg-zinc-200 dark:bg-zinc-700 rounded-md">&nbsp;</div>
                    </flux:cell>

                    <flux:cell class="max-sm:hidden">
                        <div class="w-full bg-zinc-200 dark:bg-zinc-700 rounded-md">&nbsp;</div>
                    </flux:cell>

                    <flux:cell class="max-sm:hidden">
                        <div class="w-full bg-zinc-200 dark:bg-zinc-700 rounded-md">&nbsp;</div>
                    </flux:cell>

                    <flux:cell class="max-sm:hidden">
                        <div class="w-full bg-zinc-200 dark:bg-zinc-700 rounded-md">&nbsp;</div>
                    </flux:cell>

                    <flux:cell class="max-sm:hidden">
                        <div class="w-full bg-zinc-200 dark:bg-zinc-700 rounded-md">&nbsp;</div>
                    </flux:cell>

                    <flux:cell class="max-sm:hidden">
                        <div class="w-full bg-zinc-200 dark:bg-zinc-700 rounded-md">&nbsp;</div>
                    </flux:cell>
                </flux:row>
            @endforeach
        </flux:rows>
    </flux:table>

    <span class="sr-only">Loading...</span>
</div>
