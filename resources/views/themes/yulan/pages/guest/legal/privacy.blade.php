<flux:main container>
    <div class="pt-16 mx-auto max-w-3xl space-y-12">
        <div>
            <flux:heading size="xl">
                {{ __('Privacy Policy') }}
            </flux:heading>
            <flux:text size="sm" class="mt-2">
                {{ __('Last Updated: January 2025') }}
            </flux:text>
        </div>

        <div class="space-y-2">
            <flux:heading size="lg">
                {{ __('Introduction') }}
            </flux:heading>

            <flux:text>
                {{ __('Yulan Mu (www.yulanmu.org) is committed to protecting your privacy. This policy explains what information we collect, how we use it, and your rights regarding your data.') }}
            </flux:text>
        </div>

        <div class="space-y-2">
            <flux:heading size="lg">
                {{ __('Information We Collect') }}
            </flux:heading>

            <flux:text>
                {{ __('We collect the following information:') }}
            </flux:text>

            <flux:text>
                <ul class="list-disc pl-6 space-y-2">
                    <li>{{ __('Account information (username, email)') }}</li>
                    <li>{{ __('Game progress and statistics') }}</li>
                    <li>{{ __('Payment information for virtual currency purchases') }}</li>
                    <li>{{ __('Technical information (IP address, browser type, device info)') }}</li>
                    <li>{{ __('Communications with our support team') }}</li>
                </ul>
            </flux:text>
        </div>

        <div class="space-y-2">
            <flux:heading size="lg">
                {{ __('How We Use Your Information') }}
            </flux:heading>

            <flux:text>
                {{ __('We use your information to:') }}
            </flux:text>

            <flux:text>
                <ul class="list-disc pl-6 space-y-2">
                    <li>{{ __('Provide and maintain the game service') }}</li>
                    <li>{{ __('Process your payments and deliver virtual items') }}</li>
                    <li>{{ __('Communicate with you about your account and the game') }}</li>
                    <li>{{ __('Prevent cheating and protect our services') }}</li>
                    <li>{{ __('Improve our services and fix issues') }}</li>
                </ul>
            </flux:text>
        </div>

        <div class="space-y-2">
            <flux:heading size="lg">
                {{ __('Your Rights') }}
            </flux:heading>

            <flux:text>
                {{ __('As a user, you have the right to:') }}
            </flux:text>

            <flux:text>
                <ul class="list-disc pl-6 space-y-2">
                    <li>{{ __('Access your personal data') }}</li>
                    <li>{{ __('Correct any mistakes in your information') }}</li>
                    <li>{{ __('Request deletion of your data') }}</li>
                    <li>{{ __('Object to certain uses of your data') }}</li>
                </ul>
            </flux:text>
        </div>

        <div class="space-y-2">
            <flux:heading size="lg">
                {{ __('Security') }}
            </flux:heading>

            <flux:text>
                {{ __('We take security seriously and use appropriate measures to protect your information. However, no internet transmission is completely secure, so please use strong passwords and keep your account information safe.') }}
            </flux:text>

            <flux:text>
                {{ __('Payment information is processed securely through our payment providers. We do not store complete credit card information on our servers. All transactions are protected with industry-standard encryption and security protocols.') }}
            </flux:text>
        </div>

        <div class="space-y-2">
            <flux:heading size="lg">
                {{ __('Age Restrictions') }}
            </flux:heading>

            <flux:text>
                {{ __('Our service is not intended for users under 16 years old. If you are under 16, you need parent or guardian permission to use our service.') }}
            </flux:text>
        </div>

        <div class="space-y-2">
            <flux:heading size="lg">
                {{ __('Changes to This Policy') }}
            </flux:heading>

            <flux:text>
                {{ __('We may update this policy from time to time. We will notify you of any significant changes through the website or by email.') }}
            </flux:text>
        </div>

        <div class="space-y-2">
            <flux:heading size="lg">
                {{ __('Contact Us') }}
            </flux:heading>

            <flux:text>
                {{ __('For questions about your privacy or data protection, you can:') }}
            </flux:text>

            <flux:text>
                <ul class="list-disc pl-6 space-y-2">
                    <li>
                        {{ __('Email our privacy team at') }}
                        <flux:link href="mailto:support@yulanmu.com">support@yulanmu.com</flux:link>
                    </li>
                    <li>
                        {{ __('Reach us on') }}
                        <flux:link href="{{ config('social.links.discord') }}">{{ __('Discord') }}</flux:link>
                        {{ __('for immediate assistance') }}
                    </li>
                    <li>
                        {{ __('Submit a request through our') }}
                        <flux:link href="{{ route('support') }}" wire:navigate>{{ __('Help Center') }}</flux:link>
                    </li>
                </ul>
            </flux:text>
        </div>
    </div>
</flux:main>
