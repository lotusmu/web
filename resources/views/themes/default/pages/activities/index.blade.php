<div class="space-y-6">
    <header>
        <flux:heading size="xl">
            {{ __('Activity Logs') }}
        </flux:heading>

        <x-flux::subheading>
            {{ __('View your recent activities.') }}
        </x-flux::subheading>
    </header>

    <flux:table :paginate="$this->activities" class="!overflow-x-scroll">
        <flux:columns>
            <flux:column>{{ __('Description') }}</flux:column>
            <flux:column>{{ __('Amount') }}</flux:column>
            <flux:column>{{ __('IP Address') }}</flux:column>
            <flux:column>{{ __('Date') }}</flux:column>
        </flux:columns>

        <flux:rows>
            @if ($this->activities->count() <=0)
                <flux:row>
                    <flux:cell>{{__('No Records')}}</flux:cell>
                </flux:row>
            @endif
            @foreach ($this->activities as $activity)
                <flux:row :key="$activity->id">
                    <flux:cell class="text-xs">
                        {{ $activity->description }}
                    </flux:cell>

                    <flux:cell class="text-xs">
                        <flux:badge size="sm"
                                    inset="top bottom"
                                    :color="$this->setBadgeColor($activity)"
                                    :icon="$this->getActivityType($activity)->getIcon()">
                            {{ $activity->properties['amount'] ?? __('N/A') }}
                        </flux:badge>
                    </flux:cell>

                    <flux:cell class="text-xs">
                        {{ $activity->properties['ip_address'] ?? '-' }}
                    </flux:cell>

                    <flux:cell class="text-xs">
                        {{ $activity->created_at->format('M j, Y H:i:s') }}
                    </flux:cell>
                </flux:row>
            @endforeach
        </flux:rows>
    </flux:table>
</div>