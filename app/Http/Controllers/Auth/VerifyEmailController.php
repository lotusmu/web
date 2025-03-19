<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Member\CreateMemberOnEmailVerification;
use App\Http\Controllers\Controller;
use App\Support\ActivityLog\IdentityProperties;
use Exception;
use Flux\Flux;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class VerifyEmailController extends Controller
{
    public function __construct(
        private readonly CreateMemberOnEmailVerification $createMember
    ) {}

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

            try {
                $this->createMember->handle($user);

                activity('auth')
                    ->performedOn($user)
                    ->withProperties([
                        ...IdentityProperties::capture(),
                    ])
                    ->log('Email address verified by user.');

            } catch (Exception $e) {
                Log::error('Email verified but member creation failed', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage(),
                ]);

                Flux::toast(
                    text: __('Your email was verified, but there was an issue with your account setup. Please contact support.'),
                    heading: __('Account Setup Issue'),
                    variant: 'warning'
                );
            }
        }

        return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
    }
}
