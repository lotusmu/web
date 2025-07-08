<?php

namespace App\Actions;

use App\Models\User\User;
use Illuminate\Support\Facades\Cookie;

class HandleDiscordInvitePopup
{
    private const COOKIE_FIRST_VISIT = 'discord_first_visit';

    private const COOKIE_PREFERENCES = 'discord_preferences';

    private const COOKIE_LIFETIME = 60 * 24 * 365; // 1 year in minutes

    private const MINIMUM_ENGAGEMENT_SECONDS = 30;

    private const COOLING_PERIOD_DAYS = 14;

    /**
     * Determine if the Discord popup should be shown to the user
     */
    public function shouldShow(?User $user = null): bool
    {
        // Check if this is the user's first visit to the site
        $firstVisit = (int) Cookie::get(self::COOKIE_FIRST_VISIT, 0);
        if ($firstVisit === 0) {
            // First visit - set the cookie and don't show popup yet
            Cookie::queue(self::COOKIE_FIRST_VISIT, time(), self::COOKIE_LIFETIME);

            return false;
        }

        // Don't show popup if user just arrived
        if (time() - $firstVisit < self::MINIMUM_ENGAGEMENT_SECONDS) {
            return false;
        }

        // Check user preferences (for logged-in users)
        if ($user && $user->discordPopupPreference) {
            $userPref = $user->discordPopupPreference;

            // Never show if they opted out or already joined
            if ($userPref->never_show_again || $userPref->joined_discord) {
                return false;
            }

            // Don't show again for 14 days after declining
            if ($userPref->last_declined_at &&
                $userPref->last_declined_at->diffInDays(now()) < self::COOLING_PERIOD_DAYS) {
                return false;
            }
        }

        // Check cookie preferences (for anonymous users)
        $preferences = $this->getPreferences();

        // Never show if they opted out or already joined
        if (isset($preferences['never_show_again']) && $preferences['never_show_again']) {
            return false;
        }

        if (isset($preferences['joined']) && $preferences['joined']) {
            return false;
        }

        // Don't show again for 14 days after declining
        if (isset($preferences['last_declined']) &&
            (time() - $preferences['last_declined'] < self::COOLING_PERIOD_DAYS * 24 * 60 * 60)) {
            return false;
        }

        // Don't show if recently shown
        if (isset($preferences['last_shown']) &&
            (time() - $preferences['last_shown'] < 24 * 60 * 60)) {
            return false;
        }

        return true;
    }

    /**
     * Record user response to the Discord popup
     */
    public function recordResponse(bool $joined, bool $neverShowAgain, ?User $user = null): void
    {
        $preferences = $this->getPreferences();

        $preferences['joined'] = $joined;
        $preferences['never_show_again'] = $neverShowAgain;
        $preferences['last_shown'] = time();

        if (! $joined) {
            $preferences['last_declined'] = time();
        }

        Cookie::queue(self::COOKIE_PREFERENCES, json_encode($preferences), self::COOKIE_LIFETIME);

        if ($user) {
            $user->discordPopupPreference()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'joined_discord' => $joined,
                    'never_show_again' => $neverShowAgain,
                    'last_shown_at' => now(),
                    'last_declined_at' => $joined ? null : now(),
                ]
            );
        }
    }

    /**
     * Get user preferences from cookie
     */
    private function getPreferences(): array
    {
        return json_decode(Cookie::get(self::COOKIE_PREFERENCES, '{}'), true);
    }

    /**
     * Reset all Discord popup preferences
     */
    public function resetPreferences(?User $user = null): void
    {
        Cookie::queue(Cookie::forget(self::COOKIE_FIRST_VISIT));
        Cookie::queue(Cookie::forget(self::COOKIE_PREFERENCES));

        if ($user && $user->discordPopupPreference) {
            $user->discordPopupPreference->delete();
        }
    }
}
