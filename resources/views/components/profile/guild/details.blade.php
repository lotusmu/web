@props(['guild'])

<div class="flex flex-col space-y-4 w-full">
    <x-profile.detail-row
        label="{{ __('Guild Name') }}"
        :value="$guild->G_Name"
    />

    <x-profile.detail-row
        label="{{ __('Guild Master') }}"
        :value="$guild->master->Name ?? __('None')"
    />

    <x-profile.detail-row
        label="{{ __('Members') }}"
        :value="$guild->members_count"
    />

    <flux:separator variant="subtle"/>

    <x-profile.detail-row
        label="{{ __('Total Resets') }}"
        :value="number_format($guild->characters_sum_reset_count)"
    />

    <x-profile.detail-row
        label="{{ __('CS Wins') }}"
        :value="$guild->CS_Wins"
    />

    <x-profile.detail-row
        label="{{ __('Event Score') }}"
        :value="number_format($guild->characters_sum_event_score)"
    />

    <x-profile.detail-row
        label="{{ __('Hunt Score') }}"
        :value="number_format($guild->characters_sum_hunter_score)"
    />
</div>
