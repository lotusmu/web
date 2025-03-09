<?php

namespace App\Livewire\Forms;

use App\Models\User\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Form;

class LoginForm extends Form
{
    #[Validate('required|string|min:4|max:10|alpha_num')]
    public string $name = '';

    #[Validate('required|string|max:10')]
    public string $password = '';

    #[Validate('boolean')]
    public bool $remember = false;

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();
        $this->checkIfUserIsBanned();

        if (! Auth::attempt($this->only(['name', 'password']), $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'form.name' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'form.name' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->name).'|'.request()->ip());
    }

    /**
     * Check if the user attempting to login is banned.
     *
     * @throws ValidationException
     */
    protected function checkIfUserIsBanned(): void
    {
        $user = User::where('name', $this->name)->first();

        if ($user && $user->is_banned) {
            $reason = $user->ban_reason
                ? 'Reason: '.$user->ban_reason
                : 'Contact administration for more information.';

            throw ValidationException::withMessages([
                'form.name' => 'Your account has been banned. '.$reason,
            ]);
        }
    }
}
