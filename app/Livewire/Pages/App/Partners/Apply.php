<?php

namespace App\Livewire\Pages\App\Partners;

use App\Actions\Partner\SubmitPartnerApplication;
use App\Livewire\BaseComponent;
use Flux;

class Apply extends BaseComponent
{
    public string $contentType = '';

    public array $platforms = [];

    public array $channels = [
        [
            'platform' => '',
            'name' => '',
        ],
    ];

    public string $aboutYou = '';

    public string $discordUsername = '';

    public ?int $streamingHoursPerDay = null;

    public ?int $streamingDaysPerWeek = null;

    public ?int $videosPerWeek = null;

    public ?int $contentCreationMonths = null;

    public ?int $averageLiveViewers = null;

    public ?int $averageVideoViews = null;

    public function addChannel(): void
    {
        $this->channels[] = [
            'platform' => '',
            'name' => '',
        ];
    }

    public function removeChannel($index): void
    {
        if (count($this->channels) > 1) {
            unset($this->channels[$index]);
            $this->channels = array_values($this->channels);
        }
    }

    public function getShowStreamingFieldsProperty(): bool
    {
        return in_array($this->contentType, ['streaming', 'both']);
    }

    public function getShowVideoFieldsProperty(): bool
    {
        return in_array($this->contentType, ['content', 'both']);
    }

    public function submit(SubmitPartnerApplication $action)
    {
        $rules = [
            'contentType' => 'required|string|in:streaming,content,both',
            'platforms' => 'required|array|min:1',
            'platforms.*' => 'string|in:youtube,twitch,facebook',
            'channels' => 'required|array|min:1',
            'channels.*.platform' => 'required|string',
            'channels.*.name' => 'required|string',
            'aboutYou' => 'required|string|min:50',
            'discordUsername' => 'required|string|max:37',
        ];

        // Add conditional validation for frequency fields
        if ($this->showStreamingFields) {
            $rules['streamingHoursPerDay'] = 'required|integer|min:1|max:24';
            $rules['streamingDaysPerWeek'] = 'required|integer|min:1|max:7';
            $rules['averageLiveViewers'] = 'required|integer|min:0';
        }

        if ($this->showVideoFields) {
            $rules['videosPerWeek'] = 'required|integer|min:1|max:50';
            $rules['averageVideoViews'] = 'required|integer|min:0';
        }

        // Always required for all content types
        $rules['contentCreationMonths'] = 'required|integer|min:1|max:240'; // Max 20 years

        $this->validate($rules);

        $result = $action->handle(
            auth()->user(),
            $this->contentType,
            $this->platforms,
            $this->channels,
            $this->aboutYou,
            $this->discordUsername,
            $this->streamingHoursPerDay,
            $this->streamingDaysPerWeek,
            $this->videosPerWeek,
            $this->contentCreationMonths,
            $this->averageLiveViewers,
            $this->averageVideoViews
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

    protected function getViewName(): string
    {
        return 'pages.app.partners.apply';
    }

    protected function getLayoutType(): string
    {
        return 'app';
    }
}
