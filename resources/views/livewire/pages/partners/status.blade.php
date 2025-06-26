<?php

use App\Enums\Partner\ApplicationStatus;
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

    public function getCanReapplyProperty(): bool
    {
        if ( ! $this->application || $this->application->status !== ApplicationStatus::REJECTED) {
            return false;
        }

        $sixMonthsAfter = $this->application->created_at->addMonths(6);

        return now()->greaterThanOrEqualTo($sixMonthsAfter);
    }

    public function getReapplyDateProperty(): ?string
    {
        if ( ! $this->application || $this->application->status !== ApplicationStatus::REJECTED) {
            return null;
        }

        return $this->application->created_at->addMonths(6)->format('M j, Y');
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

            @if($application->status === ApplicationStatus::REJECTED)
                <flux:card variant="outline" class="border-red-200 bg-red-50">
                    <div class="space-y-3">
                        <flux:heading>
                            {{ __('Application Not Approved') }}
                        </flux:heading>

                        @if($this->canReapply)
                            <flux:text>
                                {{ __('Thank you for your interest. You\'re now eligible to submit a new application with updated information.') }}
                            </flux:text>
                            <flux:button href="{{ route('partners.apply') }}" variant="primary" size="sm">
                                {{ __('Submit New Application') }}
                            </flux:button>
                        @else
                            <flux:text>
                                {{ __('Thank you for your interest. You may submit a new application after :date to give you time to address our feedback.', ['date' => $this->reapplyDate]) }}
                            </flux:text>
                        @endif
                    </div>
                </flux:card>
            @endif

            <!-- Rest of the template stays the same -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <flux:heading>{{ __('Content Type') }}</flux:heading>
                    <flux:subheading>{{ __(ucfirst($application->content_type)) }}</flux:subheading>
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
                    <x-prose :content="$application->notes" class="mt-2"/>
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
