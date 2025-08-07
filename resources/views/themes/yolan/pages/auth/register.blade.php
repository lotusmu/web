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