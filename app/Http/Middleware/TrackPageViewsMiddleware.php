<?php

namespace App\Http\Middleware;

use App\Actions\HandleDiscordInvitePopup;
use Closure;
use Illuminate\Http\Request;

class TrackPageViewsMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $discordAction = new HandleDiscordInvitePopup;
        $discordAction->incrementPageViews();

        return $next($request);
    }
}
