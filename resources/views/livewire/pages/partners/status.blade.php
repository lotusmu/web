<?php
// pages/partners/status.blade.php

use App\Models\Partner\PartnerApplication;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {
    public ?PartnerApplication $application = null;

    public function mount()
    {
        $this->application = PartnerApplication::where('user_id', auth()->id())
            ->latest()
            ->first();
    }
}; ?>

<div class="space-y-6">
    <header>
        <flux:heading size="xl">
            {{ __('Partner Application Status') }}
        </flux:heading>
    </header>

    @if($application)
        <flux:card class="space-y-6">
            <div class="flex items-center">
                <flux:heading size="lg">{{ __('Application Details') }}</flux:heading>

                <flux:spacer/>

                <flux:badge
                    :color="$application->status->badgeColor()"
                    :icon="$application->status->badgeIcon()"
                >
                    {{ $application->status->getLabel() }}
                </flux:badge>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <flux:heading>{{ __('Content Type') }}</flux:heading>
                    <flux:subheading>{{ ucfirst($application->content_type) }}</flux:subheading>
                </div>

                <div>
                    <flux:heading>{{ __('Platforms') }}</flux:heading>
                    <flux:subheading>{{ implode(', ', array_map('ucfirst', $application->platforms)) }}</flux:subheading>
                </div>

                <div>
                    <flux:heading>{{ __('Submitted') }}</flux:heading>
                    <flux:subheading>{{ $application->created_at->format('M j, Y \a\t g:i A') }}</flux:subheading>
                </div>

                @if($application->reviewed_at)
                    <div>
                        <flux:heading>{{ __('Reviewed') }}</flux:heading>
                        <flux:subheading>{{ $application->reviewed_at->format('M j, Y \a\t g:i A') }}</flux:subheading>
                    </div>
                @endif
            </div>

            <div>
                <flux:heading>{{ __('Channels') }}</flux:heading>
                <div class="mt-2 space-y-2">
                    @foreach($application->channels as $channel)
                        <div class="flex items-center gap-2">
                            <flux:text class="font-medium">{{ ucfirst($channel['platform']) }}:</flux:text>
                            <flux:text>{{ $channel['name'] }}</flux:text>
                        </div>
                    @endforeach
                </div>
            </div>

            @if($application->notes)
                <div>
                    <flux:heading>{{ __('Admin Notes') }}</flux:heading>
                    <flux:text class="mt-2">{{ $application->notes }}</flux:text>
                </div>
            @endif
        </flux:card>
    @else
        <flux:card>
            <div>
                <flux:heading>{{ __('No Application Found') }}</flux:heading>
                <flux:subheading>{{ __("You haven't submitted a partner application yet.") }}</flux:subheading>
            </div>

            <div class="mt-8">
                <flux:button href="{{ route('partners.apply') }}" variant="primary">
                    {{ __('Apply Now') }}
                </flux:button>
            </div>
        </flux:card>
    @endif
</div>
