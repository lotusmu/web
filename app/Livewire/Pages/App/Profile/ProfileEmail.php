<?php

namespace App\Livewire\Pages\App\Profile;

use App\Livewire\BaseComponent;
use App\Models\User\User;
use App\Rules\UnauthorizedEmailProviders;
use App\Support\ActivityLog\IdentityProperties;
use Flux;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rule;

class ProfileEmail extends BaseComponent
{
    private const MAX_ATTEMPTS = 3;

    private const DECAY_SECONDS = 300;

    public string $email = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->email = Auth::user()->email;
    }

    private function throttleKey(): string
    {
        return 'update-email:'.Auth::id();
    }

    private function ensureIsNotRateLimited(): bool
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), self::MAX_ATTEMPTS)) {
            return true;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey());

        Flux::toast(
            text: __('Too many email update attempts. Please wait :minutes minutes.', [
                'minutes' => ceil($seconds / 60),
            ]),
            heading: __('Too Many Attempts'),
            variant: 'danger'
        );

        return false;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        if (! $this->ensureIsNotRateLimited()) {
            return;
        }

        $user = Auth::user();

        $validated = $this->validate([
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user->id),
                new UnauthorizedEmailProviders,
            ],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        RateLimiter::hit($this->throttleKey());

        activity('auth')
            ->performedOn($user)
            ->withProperties([
                ...IdentityProperties::capture(),
            ])
            ->log('Updated their email address.');

        Flux::toast(
            text: __('You can always update this in your settings.'),
            heading: __('Changes saved'),
            variant: 'success',
        );
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Flux::toast(__('A new verification link has been sent to your email address.'));
    }

    protected function getViewName(): string
    {
        return 'pages.app.profile.email';
    }

    protected function getLayoutType(): string
    {
        return 'app';
    }
}
