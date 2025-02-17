@props(['guild'])

<div>
    <flux:heading size="lg" class="mb-2">
        {{ __('General Information') }}
    </flux:heading>

    <flux:separator class="mb-8"/>

    <div
        class="flex items-start justify-start sm:space-x-8 max-sm:flex-col max-sm:space-y-8">
        <div class="min-w-64">
            <img src="{{ $guild->getMarkUrl(172) }}"
                 alt="{{ $guild->G_Name }}"
                 class="sm:mx-auto shrink-0 rounded-xl border border-zinc-200 dark:border-white/10"/>
        </div>

        <x-profile.guild.details :guild="$guild"/>
    </div>
</div>
<div>

</div>
