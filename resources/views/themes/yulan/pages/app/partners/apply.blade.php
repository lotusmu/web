@php
    use App\Enums\Partner\Platform;
@endphp

<div class="space-y-6">
    <header>
        <flux:heading size="xl">
            {{ __('Partner Application') }}
        </flux:heading>

        <x-flux::subheading>
            {{ __('Join our content creator program and earn tokens through your streams and videos.') }}
        </x-flux::subheading>
    </header>

    <form wire:submit="submit" class="space-y-6">
        <div class="flex flex-col lg:flex-row gap-4 lg:gap-6">
            <div class="w-72">
                <flux:heading size="lg">{{ __('Content Type') }}</flux:heading>
                <flux:subheading>{{ __('Choose what kind of content you create.') }}</flux:subheading>
            </div>

            <div class="flex-1 space-y-6">
                <flux:select
                    variant="listbox"
                    wire:model.live="contentType"
                    :label="__('Type of Content')"
                    :description="__('Select the type of content you create')"
                    :placeholder="__('Select one...')"
                >
                    <flux:option value="streaming">{{ __('Live Streaming') }}</flux:option>
                    <flux:option value="content">{{ __('Content (Videos)') }}</flux:option>
                    <flux:option value="both">{{ __('Both') }}</flux:option>
                </flux:select>
            </div>
        </div>

        <flux:separator variant="subtle" class="my-8"/>

        <div class="flex flex-col lg:flex-row gap-4 lg:gap-6">
            <div class="w-72">
                <flux:heading size="lg">{{ __('Platforms') }}</flux:heading>
                <flux:subheading>{{ __('Where do you publish your content?') }}</flux:subheading>
            </div>

            <div class="flex-1 space-y-6">
                <flux:select
                    variant="listbox"
                    multiple
                    indicator="checkbox"
                    wire:model.live="platforms"
                    :label="__('Content Platforms')"
                    :description="__('Select all platforms where you create content')"
                    :placeholder="__('Select one or more...')"
                >
                    @foreach(Platform::cases() as $platform)
                        <flux:option value="{{ $platform->value }}">{{ $platform->getLabel() }}</flux:option>
                    @endforeach
                </flux:select>
            </div>
        </div>

        <flux:separator variant="subtle" class="my-8"/>

        <div class="flex flex-col lg:flex-row gap-4 lg:gap-6">
            <div class="w-72">
                <flux:heading size="lg">{{ __('Channel Information') }}</flux:heading>
                <flux:subheading>{{ __('Tell us where to find your content.') }}</flux:subheading>
            </div>

            <div class="flex-1 space-y-6">
                @foreach($channels as $index => $channel)
                    <div class="space-y-6">
                        <div class="flex justify-between items-center">
                            <p class="font-medium">{{ __('Channel') }} #{{ $index + 1 }}</p>

                            @if(count($channels) > 1)
                                <flux:button
                                    square variant="ghost"
                                    wire:click="removeChannel({{ $index }})"
                                >
                                    <flux:icon.trash variant="mini" class="text-red-500"/>
                                </flux:button>
                            @endif
                        </div>

                        <flux:select
                            wire:model="channels.{{ $index }}.platform"
                            variant="listbox"
                            :label="__('Platform')"
                            :placeholder="__('Select platform...')"
                            required
                        >
                            @foreach($platforms as $platformValue)
                                @php $platform = Platform::tryFrom($platformValue); @endphp
                                @if($platform)
                                    <flux:option value="{{ $platformValue }}">
                                        {{ $platform->getLabel() }}
                                    </flux:option>
                                @endif
                            @endforeach
                        </flux:select>

                        <flux:input
                            wire:model="channels.{{ $index }}.name"
                            :label="__('Channel Name')"
                            required
                            :placeholder="__('Enter your channel name')"
                        />
                    </div>

                    <flux:separator variant="subtle" class="my-8"/>
                @endforeach

                <div>
                    <flux:button
                        type="button"
                        wire:click="addChannel"
                        icon="plus"
                        class="flex items-center gap-1"
                    >
                        {{ __('Add Another') }}
                    </flux:button>
                </div>
            </div>
        </div>

        <!-- Content Frequency Section -->
        @if($this->showStreamingFields || $this->showVideoFields)
            <flux:separator variant="subtle" class="my-8"/>

            <div class="flex flex-col lg:flex-row gap-4 lg:gap-6">
                <div class="w-72">
                    <flux:heading size="lg">{{ __('Content Schedule') }}</flux:heading>
                    <flux:subheading>{{ __('Help us understand your content frequency.') }}</flux:subheading>
                </div>

                <div class="flex-1 space-y-6">
                    <!-- Content Creation Experience (always shown) -->
                    <flux:input
                        type="number"
                        wire:model="contentCreationMonths"
                        :label="__('Content creation experience')"
                        :description="__('How many months have you been creating content?')"
                        :placeholder="__('e.g., 12')"
                        min="1"
                        max="240"
                        required
                    />

                    @if($this->showStreamingFields)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <flux:input
                                type="number"
                                wire:model="streamingHoursPerDay"
                                :label="__('Hours per day streaming')"
                                :description="__('How many hours do you typically stream each day?')"
                                :placeholder="__('e.g., 4')"
                                min="1"
                                max="24"
                                required
                            />

                            <flux:input
                                type="number"
                                wire:model="streamingDaysPerWeek"
                                :label="__('Days per week streaming')"
                                :description="__('How many days per week do you go live?')"
                                :placeholder="__('e.g., 5')"
                                min="1"
                                max="7"
                                required
                            />
                        </div>

                        <flux:input
                            type="number"
                            wire:model="averageLiveViewers"
                            :label="__('Average live viewers')"
                            :description="__('Average number of concurrent viewers during your streams')"
                            :placeholder="__('e.g., 50')"
                            min="0"
                            required
                        />
                    @endif

                    @if($this->showVideoFields)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <flux:input
                                type="number"
                                wire:model="videosPerWeek"
                                :label="__('Videos per week')"
                                :description="__('How many videos do you typically publish each week?')"
                                :placeholder="__('e.g., 3')"
                                min="1"
                                max="50"
                                required
                            />

                            <flux:input
                                type="number"
                                wire:model="averageVideoViews"
                                :label="__('Average video views')"
                                :description="__('Average views per video across your content')"
                                :placeholder="__('e.g., 1000')"
                                min="0"
                                required
                            />
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <flux:separator variant="subtle" class="my-8"/>

        <div class="flex flex-col gap-4 lg:gap-6">
            <div>
                <flux:heading size="lg">{{ __('About You') }}</flux:heading>
                <flux:subheading>{{ __('Tell us more about you and your content') }}</flux:subheading>
            </div>

            <flux:editor
                toolbar="bold italic underline | bullet ordered highlight | link ~ undo redo"
                wire:model="aboutYou"
                required
                :placeholder="__('Share details about your content, experience, and what you have created so far...')"
            />

            <flux:input
                wire:model="discordUsername"
                :label="__('Discord')"
                :description="__('Your Discord username so we can contact you')"
                :placeholder="__('e.g., username#1234 or @username')"
                maxlength="37"
            />
        </div>

        <div class="flex justify-end">
            <flux:button type="submit" variant="primary">
                {{ __('Submit Application') }}
            </flux:button>
        </div>
    </form>
</div>
