<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Member\SyncMember;
use App\Http\Controllers\Controller;
use App\Models\User\TemporaryPassword;
use App\Support\ActivityLog\IdentityProperties;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));

            // Create Member account if not already created
            if (! $user->member_created) {
                // Get the stored password
                $tempPassword = TemporaryPassword::where('user_id', $user->id)->first();

                if ($tempPassword) {
                    // Directly create Member with the stored password
                    $syncMember = app(SyncMember::class);
                    $syncMember->createWithPassword($user, $tempPassword->password);

                    // Clean up
                    $tempPassword->delete();
                } else {
                    // If no temporary password found, create a random one for both systems
                    $newPassword = Str::random(10);

                    // Update user password
                    $user->forceFill([
                        'password' => Hash::make($newPassword),
                    ])->saveQuietly();

                    // Create Member with this password
                    $syncMember = app(SyncMember::class);
                    $syncMember->createWithPassword($user, $newPassword);

                    // You might want to notify the user about this password reset
                }
            }

            activity('auth')
                ->performedOn($user)
                ->withProperties([
                    ...IdentityProperties::capture(),
                ])
                ->log('Email address verified by user.');
        }

        return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
    }
}
