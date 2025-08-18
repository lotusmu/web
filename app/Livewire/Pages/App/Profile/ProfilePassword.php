<?php

namespace App\Livewire\Pages\App\Profile;

use App\Livewire\BaseComponent;
use App\Support\ActivityLog\IdentityProperties;
use Flux;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class ProfilePassword extends BaseComponent
{
    private const MAX_ATTEMPTS = 3;

    private const DECAY_SECONDS = 300;

    public string $current_password = '';

    public string $password = '';

    public string $password_confirmation = '';

    private function throttleKey(): string
    {
        return 'update-password:'.Auth::id();
    }

    private function ensureIsNotRateLimited(): bool
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), self::MAX_ATTEMPTS)) {
            return true;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey());

        Flux::toast(
            text: __('Too many password update attempts. Please wait :minutes minutes.', [
                'minutes' => ceil($seconds / 60),
            ]),
            heading: __('Too Many Attempts'),
            variant: 'danger'
        );

        return false;
    }

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        if (! $this->ensureIsNotRateLimited()) {
            return;
        }

        $user = Auth::user();

        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', 'confirmed', 'min:6', 'max:10'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        $user->update([
            'password' => $validated['password'],
        ]);

        RateLimiter::hit($this->throttleKey());

        $this->reset('current_password', 'password', 'password_confirmation');

        activity('auth')
            ->performedOn($user)
            ->withProperties([
                ...IdentityProperties::capture(),
            ])
            ->log('Updated their password.');

        Flux::toast(
            text: __('You can always update this in your settings.'),
            heading: __('Changes saved'),
            variant: 'success',
        );
    }

    protected function getViewName(): string
    {
        return 'pages.app.profile.password';
    }

    protected function getLayoutType(): string
    {
        return 'app';
    }
}
