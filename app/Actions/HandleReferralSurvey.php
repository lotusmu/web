<?php

namespace App\Actions;

use App\Models\User\User;
use Illuminate\Support\Facades\Cache;

class HandleReferralSurvey
{
    private const CACHE_KEY_PREFIX = 'referral_survey_status_';

    private const CACHE_TTL = 86400; // 24 hours

    /**
     * Determine if the survey should be shown to the user.
     */
    public function shouldShowSurvey(?User $user = null): bool
    {
        if (! $user) {
            return false;
        }

        // Check cache first to avoid DB query
        $cacheKey = self::CACHE_KEY_PREFIX.$user->id;
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        // Check if user meets time requirement
        $daysSinceRegistration = $user->created_at->diffInDays(now());
        if ($daysSinceRegistration < 1) {
            Cache::put($cacheKey, false, self::CACHE_TTL);

            return false;
        }

        // Get survey record (with efficient query)
        $survey = $user->referralSurvey()->select(['completed', 'dismissed'])->first();

        // Don't show if completed or dismissed
        if ($survey && ($survey->completed || $survey->dismissed)) {
            Cache::put($cacheKey, false, self::CACHE_TTL);

            return false;
        }

        // Show the survey
        Cache::put($cacheKey, true, self::CACHE_TTL);

        return true;
    }

    /**
     * Record a response to the survey.
     */
    public function recordResponse(
        User $user,
        ?string $referralSource = null,
        ?string $mmoTopSite = null,
        ?string $muOnlineForum = null,
        ?string $customSource = null,
        bool $dismissed = false
    ): void {
        $completed = ! $dismissed;

        $user->referralSurvey()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'referral_source' => $referralSource,
                'mmo_top_site' => $mmoTopSite,
                'mu_online_forum' => $muOnlineForum,
                'custom_source' => $customSource,
                'completed' => $completed,
                'dismissed' => $dismissed,
                'shown_at' => now(),
            ]
        );

        // Update cache - survey should no longer show
        Cache::put(self::CACHE_KEY_PREFIX.$user->id, false, self::CACHE_TTL);
    }

    /**
     * Mark that the survey has been shown to the user.
     */
    public function markAsShown(User $user): void
    {
        $survey = $user->referralSurvey()->first();

        if (! $survey) {
            // Create a new record if one doesn't exist
            $user->referralSurvey()->create([
                'shown_at' => now(),
            ]);
        } else {
            // Update existing record
            $survey->shown_at = now();
            $survey->save();
        }
    }
}
