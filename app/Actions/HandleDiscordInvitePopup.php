<?php

namespace App\Actions;

use App\Models\User\User;
use Illuminate\Support\Facades\Session;

class HandleDiscordInvitePopup
{
    public function handle(?User $user = null): bool
    {
        if (! $user) {
            return $this->handleAnonymousUser();
        }

        return $this->handleAuthenticatedUser($user);
    }

    private function handleAnonymousUser(): bool
    {
        $sessionKey = 'discord_popup';
        $pageViews = Session::get('page_views', 0);

        if (Session::has($sessionKey)) {
            $popupData = Session::get($sessionKey);

            if ($popupData['never_show_again'] ?? false) {
                return false;
            }

            $lastShown = $popupData['last_shown'] ?? 0;
            if (time() - $lastShown < 3 * 24 * 60 * 60) {
                return false;
            }
        }

        $timeOnSite = Session::get('first_visit_time', time()) - time();
        if ($pageViews >= 3 && abs($timeOnSite) >= 120) {
            return true;
        }

        return false;
    }

    private function handleAuthenticatedUser(User $user): bool
    {
        $userPreference = $user->discordPopupPreference;
        $pageViews = Session::get('page_views', 0);

        if ($userPreference) {
            if ($userPreference->never_show_again) {
                return false;
            }

            if ($userPreference->last_declined_at &&
                $userPreference->last_declined_at->diffInDays(now()) < 14) {
                return false;
            }

            if ($userPreference->joined_discord) {
                return false;
            }
        }

        $timeOnSite = Session::get('first_visit_time', time()) - time();
        if ($pageViews >= 3 && abs($timeOnSite) >= 120) {
            return true;
        }

        return false;
    }

    public function recordResponse(bool $joined, bool $neverShowAgain, ?User $user = null): void
    {
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
        } else {
            Session::put('discord_popup', [
                'joined' => $joined,
                'never_show_again' => $neverShowAgain,
                'last_shown' => time(),
                'last_declined' => $joined ? null : time(),
            ]);
        }
    }
}
