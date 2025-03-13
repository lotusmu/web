<?php

namespace App\Actions;

use App\Models\User\User;
use Illuminate\Support\Facades\Session;

class HandleDiscordInvitePopup
{
    public function handle(?User $user = null): bool
    {
        // Anonymous users get different rules than logged-in users
        if (! $user) {
            return $this->handleAnonymousUser();
        }

        return $this->handleAuthenticatedUser($user);
    }

    private function handleAnonymousUser(): bool
    {
        $sessionKey = 'discord_popup';
        $pageViews = Session::get('page_views', 0);

        // If user has already declined
        if (Session::has($sessionKey)) {
            $popupData = Session::get($sessionKey);

            // Never show again if they selected that option
            if ($popupData['never_show_again'] ?? false) {
                return false;
            }

            // If declined recently, don't show for at least 3 days
            $lastShown = $popupData['last_shown'] ?? 0;
            if (time() - $lastShown < 3 * 24 * 60 * 60) {
                return false;
            }
        }

        // Show after at least 3 page views in a session
        // AND at least 2 minutes on site
        $timeOnSite = Session::get('first_visit_time', time()) - time();
        if ($pageViews >= 3 && abs($timeOnSite) >= 120) {
            return true;
        }

        return false;
    }

    private function handleAuthenticatedUser(User $user): bool
    {
        // Use DB instead of session for persistence
        $userPreference = $user->discordPopupPreference;

        if ($userPreference) {
            // Never show again if they selected that option
            if ($userPreference->never_show_again) {
                return false;
            }

            // If declined, wait at least 2 weeks before showing again
            if ($userPreference->last_declined_at &&
                $userPreference->last_declined_at->diffInDays(now()) < 14) {
                return false;
            }

            // If already joined Discord, don't show
            if ($userPreference->joined_discord) {
                return false;
            }
        }

        // Show after a certain number of days since registration
        // instead of relying on page_views
        $daysSinceRegistration = $user->created_at->diffInDays(now());
        if ($daysSinceRegistration >= 3) {
            return true;
        }

        return false;
    }

    public function recordResponse(bool $joined, bool $neverShowAgain, ?User $user = null): void
    {
        if ($user) {
            // Record in database
            $user->discordPopupPreference()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'joined_discord' => $joined,
                    'never_show_again' => $neverShowAgain,
                    'last_shown_at' => now(),
                    'last_declined_at' => $joined ? null : now(),
                ]
            );
        } else {
            // Record in session
            Session::put('discord_popup', [
                'joined' => $joined,
                'never_show_again' => $neverShowAgain,
                'last_shown' => time(),
                'last_declined' => $joined ? null : time(),
            ]);
        }
    }
}
