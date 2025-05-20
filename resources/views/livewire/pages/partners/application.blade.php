<?php

use App\Actions\Partner\SubmitPartnerApplication;
use App\Enums\Partner\Platform;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {
    public string $contentType;
    public array $platforms = [];
    public array $channels = [
        [
            'platform' => '',
            'name'     => ''
        ]
    ];
    public string $aboutYou = '';

    public function addChannel(): void
    {
        $this->channels[] = [
            'platform' => '',
            'name'     => ''
        ];
    }

    public function removeChannel($index): void
    {
        if (count($this->channels) > 1) {
            unset($this->channels[$index]);
            $this->channels = array_values($this->channels);
        }
    }

    public function submit(SubmitPartnerApplication $action)
    {
        $this->validate([
            'contentType'         => 'required|string|in:streaming,content,both',
            'platforms'           => 'required|array|min:1',
            'platforms.*'         => 'string|in:youtube,twitch,tiktok,facebook',
            'channels'            => 'required|array|min:1',
            'channels.*.platform' => 'required|string',
            'channels.*.name'     => 'required|string',
            'aboutYou'            => 'required|string|min:50',
        ]);

        $result = $action->handle(
            auth()->user(),
            $this->contentType,
            $this->platforms,
            $this->channels,
            $this->aboutYou
        );

        if ($result['success']) {
            Flux::toast(
                text: __('Your application has been submitted successfully!'),
                heading: __('Success'),
                variant: 'success',
            );

            return redirect()->route('partners.status');
        } else {
            Flux::toast(
                text: $result['message'],
                heading: __('Error'),
                variant: 'danger',
            );
        }
    }
}; ?>

<div class="space-y-6">
    <header>
        <flux:heading size="xl">
            {{ __('Partner Application') }}
        </flux:heading>

        <x-flux::subheading>
            {{ __('Join our content creator program and earn rewards through your streams and videos.') }}
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
                    wire:model="contentType"
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
                            :label="__('Channel Name/URL')"
                            required
                            :placeholder="__('Enter your channel name or URL')"
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
        </div>

        <div class="flex justify-end">
            <flux:button type="submit" variant="primary">
                {{ __('Submit Application') }}
            </flux:button>
        </div>
    </form>
</div>
