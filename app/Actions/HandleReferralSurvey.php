<?php

namespace App\Actions;

use App\Models\User\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class HandleReferralSurvey
{
    private const CACHE_KEY_PREFIX = 'referral_survey_status_';

    private const CACHE_TTL = 86400; // 24 hours

    private const MIN_ACCOUNT_AGE_DAYS = 1;

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

        $this->clearSurveyCache($user);
    }

    /**
     * Check if survey should be shown and mark as shown in one atomic operation.
     */
    public function checkAndMarkShown(User $user): bool
    {
        if (! $user) {
            return false;
        }

        $cacheKey = self::CACHE_KEY_PREFIX.$user->id;
        $cachedValue = Cache::get($cacheKey);

        if ($cachedValue !== null) {
            return $cachedValue;
        }

        return DB::transaction(function () use ($user, $cacheKey) {
            $daysSinceRegistration = $user->created_at->diffInDays(now());
            if ($daysSinceRegistration < self::MIN_ACCOUNT_AGE_DAYS) {
                Cache::put($cacheKey, false, self::CACHE_TTL);

                return false;
            }

            $survey = $user->referralSurvey()
                ->select(['id', 'completed', 'dismissed'])
                ->lockForUpdate()
                ->first();

            if ($survey && ($survey->completed || $survey->dismissed)) {
                Cache::put($cacheKey, false, self::CACHE_TTL);

                return false;
            }

            $user->referralSurvey()->updateOrCreate(
                ['user_id' => $user->id],
                ['shown_at' => now()]
            );

            Cache::put($cacheKey, true, self::CACHE_TTL);

            return true;
        });
    }

    private function clearSurveyCache(User $user): void
    {
        Cache::forget(self::CACHE_KEY_PREFIX.$user->id);
    }
}
