<?php

namespace App\Actions;

use App\Models\User\User;
use Illuminate\Support\Facades\Cookie;

class HandleDiscordInvitePopup
{
    private const COOKIE_PAGE_VIEWS = 'discord_page_views';

    private const COOKIE_FIRST_VISIT = 'discord_first_visit';

    private const COOKIE_PREFERENCES = 'discord_preferences';

    private const COOKIE_LIFETIME = 60 * 24 * 365; // 1 year in minutes

    public function handle(?User $user = null): bool
    {
        $preferences = json_decode(Cookie::get(self::COOKIE_PREFERENCES, '{}'), true);

        if (isset($preferences['never_show_again']) && $preferences['never_show_again']) {
            return false;
        }

        if (isset($preferences['last_declined']) &&
            (time() - $preferences['last_declined'] < 3 * 24 * 60 * 60)) {
            return false;
        }

        if (isset($preferences['joined']) && $preferences['joined']) {
            return false;
        }

        if ($user) {
            $userPreference = $user->discordPopupPreference;
            if ($userPreference) {
                if ($userPreference->never_show_again || $userPreference->joined_discord) {
                    return false;
                }

                if ($userPreference->last_declined_at &&
                    $userPreference->last_declined_at->diffInDays(now()) < 14) {
                    return false;
                }
            }
        }

        $pageViews = (int) Cookie::get(self::COOKIE_PAGE_VIEWS, 0);
        $firstVisit = (int) Cookie::get(self::COOKIE_FIRST_VISIT, 0);

        if ($pageViews >= 5 && $firstVisit > 0 && (time() - $firstVisit) >= 120) {
            return true;
        }

        return false;
    }

    public function recordResponse(bool $joined, bool $neverShowAgain, ?User $user = null): void
    {
        $preferences = [
            'joined' => $joined,
            'never_show_again' => $neverShowAgain,
            'last_shown' => time(),
            'last_declined' => $joined ? null : time(),
        ];

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

    public function incrementPageViews(): void
    {
        $pageViews = (int) Cookie::get(self::COOKIE_PAGE_VIEWS, 0);
        Cookie::queue(self::COOKIE_PAGE_VIEWS, $pageViews + 1, 60 * 24 * 7); // 1 week

        if (! Cookie::has(self::COOKIE_FIRST_VISIT)) {
            Cookie::queue(self::COOKIE_FIRST_VISIT, time(), 60 * 24 * 7); // 1 week
        }
    }

    public function clearCookies(): void
    {
        Cookie::queue(Cookie::forget(self::COOKIE_PAGE_VIEWS));
        Cookie::queue(Cookie::forget(self::COOKIE_FIRST_VISIT));
        Cookie::queue(Cookie::forget(self::COOKIE_PREFERENCES));
    }

    public function resetPreferences(?User $user = null): void
    {
        $this->clearCookies();

        if ($user && $user->discordPopupPreference) {
            $user->discordPopupPreference->delete();
        }
    }
}
