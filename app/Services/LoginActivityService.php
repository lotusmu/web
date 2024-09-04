<?php

namespace App\Services;

use App\Support\ActivityLog\IdentityProperties;
use Illuminate\Http\Request;

class LoginActivityService
{
    public function record(Request $request, $user): void
    {
        activity('auth')
            ->performedOn($user)
            ->withProperties([
                ...IdentityProperties::capture(),
            ])
            ->log(':subject.name logged in successfully');
    }
}
