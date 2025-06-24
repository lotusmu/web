<?php

use App\Models\User\User;
use App\Support\ActivityLog\IdentityProperties;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use RyanChandler\LaravelCloudflareTurnstile\Rules\Turnstile;

new #[Layout('layouts.auth')] class extends Component {
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
                'name'              => [
                    'required', 'string', 'alpha_num:ascii', 'min:4', 'max:10', 'unique:'.User::class
                ],
                'email'             => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
                'password'          => [
                    'required', 'string', 'confirmed', 'min:6', 'max:10'
                ],
                'terms'             => ['accepted'],
                'turnstileResponse' => app()->environment(['production']) ? ['required', $turnstile] : [],
            ],
            [
                'terms.accepted'              => __('You must agree to the terms and conditions to continue.'),
                'turnstileResponse.required'  => __('Please complete the CAPTCHA verification.'),
                'turnstileResponse.turnstile' => __('CAPTCHA verification failed. Please try again.'),
            ]
        );

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
}; ?>

<div class="space-y-6">
    <div>
        <flux:heading size="xl" class="text-center">
            {{__('Get started in minutes')}}
        </flux:heading>

        <flux:subheading class="text-center">
            {{__('First, let\'s create your account. Once your account has been created you must verify it in order to play Lotus Mu.')}}
        </flux:subheading>
    </div>

    <form wire:submit="register" class="flex flex-col gap-6">
        <flux:input wire:model="name" label="{{__('Username')}}"/>

        <flux:input wire:model="email"
                    label="{{__('Email')}}"
                    description="{{__('Email verification required to play.')}}"/>

        <flux:input viewable wire:model="password" type="password" label="{{__('Password')}}"/>

        <flux:input viewable wire:model="password_confirmation" type="password" label="{{__('Confirm Password')}}"/>

        <flux:field variant="inline">
            <flux:checkbox wire:model="terms"/>
            <flux:label>
                {{__('I agree to the ')}}
                <flux:link href="{{ route('terms') }}" target="_blank">{{ __('Terms of Service') }}</flux:link>
                {{ __(' and ') }}
                <flux:link href="{{ route('privacy') }}" target="_blank">{{ __('Privacy Policy') }}</flux:link>
            </flux:label>

            <flux:error name="terms"/>
        </flux:field>

        <flux:field>
            <x-turnstile wire:model="turnstileResponse"
                         data-size="flexible"
            />

            <flux:error name="turnstileResponse"/>
        </flux:field>

        <flux:button variant="primary" type="submit">
            {{ __('Register') }}
        </flux:button>
    </form>

    <flux:subheading class="text-center">
        {{__('Already have an account?')}}
        <flux:link :href="route('login')" wire:navigate>{{__('Log in!')}}</flux:link>
    </flux:subheading>
</div>

@push('scripts')
    @turnstileScripts()
@endpush
