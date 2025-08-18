<?php

namespace App\Livewire\Pages\Auth;

use App\Livewire\BaseComponent;
use App\Models\User\User;
use App\Rules\UnauthorizedEmailProviders;
use App\Support\ActivityLog\IdentityProperties;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use RyanChandler\LaravelCloudflareTurnstile\Rules\Turnstile;

class Register extends BaseComponent
{
    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public bool $terms = false;

    public $turnstileResponse = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(Turnstile $turnstile): void
    {
        $validated = $this->validate(
            [
                'name' => [
                    'required', 'string', 'alpha_num:ascii', 'min:4', 'max:10', 'unique:'.User::class,
                ],
                'email' => [
                    'required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class,
                    new UnauthorizedEmailProviders,
                ],
                'password' => [
                    'required', 'string', 'confirmed', 'min:6', 'max:10',
                ],
                'terms' => ['accepted'],
                'turnstileResponse' => app()->environment(['production']) ? ['required', $turnstile] : [],
            ],
            [
                'terms.accepted' => __('You must agree to the terms and conditions to continue.'),
                'turnstileResponse.required' => __('Please complete the CAPTCHA verification.'),
                'turnstileResponse.turnstile' => __('CAPTCHA verification failed. Please try again.'),
            ]
        );

        $validated['terms_agreed_at'] = now();

        unset($validated['terms']);
        unset($validated['turnstileResponse']);

        event(new Registered($user = User::create($validated)));

        activity('auth')
            ->performedOn($user)
            ->withProperties([
                ...IdentityProperties::capture(),
            ])
            ->log("New user registration: {$user->name}");

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }

    protected function getViewName(): string
    {
        return 'pages.auth.register';
    }

    protected function getLayoutType(): string
    {
        return 'auth';
    }
}
