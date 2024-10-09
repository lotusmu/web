<?php

namespace App\Listeners;

use App\Support\ActivityLog\IdentityProperties;
use Illuminate\Auth\Events\Login;

class LoginListener
{
    public function handle(Login $event): void
    {
        $this->recordLoginActivity($event->user);
    }

    public function recordLoginActivity($user): void
    {
        activity('auth')
            ->performedOn($user)
            ->withProperties([
                ...IdentityProperties::capture(),
            ])
            ->log('Logged in successfully.');
    }
}
