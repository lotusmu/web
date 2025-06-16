<?php

namespace App\Actions\Partner;

use App\Enums\Partner\ApplicationStatus;
use App\Models\Partner\PartnerApplication;
use App\Models\User\User;

class SubmitPartnerApplication
{
    public function handle(
        User $user,
        string $contentType,
        array $platforms,
        array $channels,
        string $aboutYou,
        ?string $discordUsername = null,
        ?int $streamingHoursPerDay = null,
        ?int $streamingDaysPerWeek = null,
        ?int $videosPerWeek = null,
        ?int $contentCreationMonths = null,
        ?int $averageLiveViewers = null,
        ?int $averageVideoViews = null
    ): array {
        $existingApplication = PartnerApplication::where('user_id', $user->id)
            ->whereIn('status', [ApplicationStatus::PENDING, ApplicationStatus::APPROVED])
            ->first();

        if ($existingApplication) {
            return [
                'success' => false,
                'message' => __('You already have a pending or approved application.'),
            ];
        }

        $application = PartnerApplication::create([
            'user_id' => $user->id,
            'content_type' => $contentType,
            'platforms' => $platforms,
            'channels' => $channels,
            'about_you' => $aboutYou,
            'discord_username' => $discordUsername,
            'streaming_hours_per_day' => $streamingHoursPerDay,
            'streaming_days_per_week' => $streamingDaysPerWeek,
            'videos_per_week' => $videosPerWeek,
            'content_creation_months' => $contentCreationMonths,
            'average_live_viewers' => $averageLiveViewers,
            'average_video_views' => $averageVideoViews,
            'status' => ApplicationStatus::PENDING,
        ]);

        return [
            'success' => true,
            'application' => $application,
        ];
    }
}
