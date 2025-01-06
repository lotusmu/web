<?php

use App\Models\User\User;
use App\Support\ActivityLog\IdentityProperties;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.auth')] class extends Component {
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public bool $terms = false;


    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name'     => ['required', 'string', 'alpha_num', 'min:4', 'max:10', 'unique:'.User::class],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'terms'    => ['accepted'],
        ]);

        unset($validated['terms']);

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
        <flux:input wire:model="email" label="{{__('Email')}}"/>
        <flux:input viewable wire:model="password" type="password" label="{{__('Password')}}"/>
        <flux:input viewable wire:model="password_confirmation" type="password" label="{{__('Confirm Password')}}"/>

        <flux:field variant="inline">
            <flux:checkbox wire:model="terms"/>
            <flux:label>
                {{__('I agree to the ')}}
                <flux:link href="{{ route('terms') }}" target="_blank">{{ __('terms and conditions') }}</flux:link>
            </flux:label>
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
