<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    //
}; ?>

<flux:main container>
    <div class="pt-16 mx-auto max-w-3xl space-y-12">
        <div>
            <flux:heading size="xl">
                {{ __('Terms of Service') }}
            </flux:heading>
            <flux:text size="sm" class="mt-2">
                {{ __('Last Updated: January 2025') }}
            </flux:text>
        </div>

        <div class="space-y-2">
            <flux:heading size="lg">
                {{ __('Acceptance of Terms') }}
            </flux:heading>

            <flux:text>
                {{ __('Welcome to Lotus Mu. By accessing and using our services, you agree to these Terms of Service ("TOS"). These terms constitute a legally binding agreement between you and Lotus Mu. We may update these terms at any time, and will notify you of any material changes via email or through our service.') }}
            </flux:text>

            <flux:text>
                {{ __('Lotus Mu refers to Lotus Mu LLC and its Affiliates, registered in Bulgaria. Throughout these terms, we may refer to Lotus Mu as "We" or "Us." Your continued use of the Service after any updates indicates your acceptance of the revised terms.') }}
            </flux:text>
        </div>

        <div class="space-y-2">
            <flux:heading size="lg">
                {{ __('Age Requirements and Eligibility') }}
            </flux:heading>

            <flux:text>
                {{ __('You must be at least 16 years old to use our Service. If you are between 16 and 18 years old, you represent that you have your parent or legal guardian\'s permission to use the Service. Some features may require you to be 18 years or older.') }}
            </flux:text>
        </div>

        <div class="space-y-2">
            <flux:heading size="lg">
                {{ __('Description of Service') }}
            </flux:heading>

            <flux:text>
                {{ __('Lotus Mu provides access to an online game service, including virtual currency, items, communication tools, forums, and related services (collectively, the "Service"). We reserve the right to modify, suspend, or discontinue any aspect of the Service at any time.') }}
            </flux:text>
        </div>

        <div class="space-y-2">
            <flux:heading size="lg">
                {{ __('Registration and Account Security') }}
            </flux:heading>

            <flux:text>
                {{ __('You agree to:') }}
            </flux:text>

            <flux:text>
                <ul class="list-disc pl-6 space-y-2">
                    <li>{{ __('Provide accurate and complete registration information') }}</li>
                    <li>{{ __('Maintain the security of your account credentials') }}</li>
                    <li>{{ __('Promptly update any changes to your information') }}</li>
                    <li>{{ __('Accept responsibility for all activities under your account') }}</li>
                </ul>
            </flux:text>
        </div>

        <div class="space-y-2">
            <flux:heading size="lg">
                {{ __('Prohibited Activities') }}
            </flux:heading>

            <flux:text>
                {{ __('The following activities are strictly prohibited:') }}
            </flux:text>

            <flux:text>
                <ul class="list-disc pl-6 space-y-2">
                    <li>{{ __('Using cheats, automation software, bots, hacks, or any unauthorized third-party software') }}</li>
                    <li>{{ __('Exploiting bugs or engaging in any form of game manipulation') }}</li>
                    <li>{{ __('Selling or trading accounts or virtual items outside of official channels') }}</li>
                    <li>{{ __('Engaging in any form of harassment or abusive behavior') }}</li>
                    <li>{{ __('Violating any applicable laws or regulations') }}</li>
                </ul>
            </flux:text>
        </div>

        <div class="space-y-2">
            <flux:heading size="lg">
                {{ __('Community Guidelines') }}
            </flux:heading>

            <flux:text>
                {{ __('By using our Service, you agree to follow our') }}
                <flux:link href="{{ route('guidelines') }}" wire:navigate>{{ __('Community Guidelines') }}</flux:link>
                {{ __(', which form an integral part of these Terms. These guidelines detail the standards of behavior, game rules, and penalties for violations.') }}
            </flux:text>
        </div>

        <div class="space-y-2">
            <flux:heading size="lg">
                {{ __('Virtual Currency and Items') }}
            </flux:heading>

            <flux:text>
                {{ __('Virtual currency and items are licensed, not sold, to you. You have no property rights in virtual items. We reserve the right to modify or remove virtual items at any time. Please refer to our Refund Policy for information about purchases.') }}
            </flux:text>

            <flux:text>
                {{ __('Charges on your bank or credit card statement will appear as "*Lotus Mu", "Lotusmu.Org" or something similar.') }}
            </flux:text>
        </div>

        <div class="space-y-2">
            <flux:heading size="lg">
                {{ __('Termination') }}
            </flux:heading>

            <flux:text>
                {{ __('We reserve the right to suspend or terminate your account for any violation of these terms, inappropriate behavior, or for any other reason at our discretion. Upon termination, you will lose access to your account and any virtual items or currency associated with it.') }}
            </flux:text>

            <flux:text>
                {{ __('You may request account termination at any time by submitting a support ticket. Upon verification, we will close your account and delete associated personal data in accordance with our data retention policies and applicable law.') }}
            </flux:text>
        </div>

        <div class="space-y-2">
            <flux:heading size="lg">
                {{ __('Intellectual Property') }}
            </flux:heading>

            <flux:text>
                {{ __('All content, including but not limited to game assets, logos, trademarks, and software, is the property of Lotus Mu or its licensors and is protected by intellectual property laws. You may not copy, modify, or distribute any content without our explicit permission.') }}
            </flux:text>
        </div>

        <div class="space-y-2">
            <flux:heading size="lg">
                {{ __('Privacy and Data Protection') }}
            </flux:heading>

            <flux:text>
                {{ __('We process personal data in accordance with our Privacy Policy and applicable data protection laws, including the EU General Data Protection Regulation (GDPR). By using our Service, you consent to our collection and processing of your personal data as described in our Privacy Policy.') }}
            </flux:text>
        </div>

        <div class="space-y-2">
            <flux:heading size="lg">
                {{ __('Governing Law') }}
            </flux:heading>

            <flux:text>
                {{ __('These terms are governed by the laws of Bulgaria and the European Union. Any disputes shall be subject to the exclusive jurisdiction of the courts of Bulgaria, without prejudice to mandatory consumer protection laws in your jurisdiction.') }}
            </flux:text>
        </div>

        <div class="space-y-2">
            <flux:heading size="lg">
                {{ __('Changes to Terms') }}
            </flux:heading>

            <flux:text>
                {{ __('We may modify these terms at any time. We will notify you of material changes via email or through our Service. Your continued use of the Service after such modifications constitutes acceptance of the updated terms.') }}
            </flux:text>
        </div>

        <div class="space-y-2">
            <flux:heading size="lg">
                {{ __('Contact Information') }}
            </flux:heading>

            <flux:text>
                {{ __('For questions about these terms or any concerns, you can:') }}
            </flux:text>

            <flux:text>
                <ul class="list-disc pl-6 space-y-2">
                    <li>
                        {{ __('Email us at') }}
                        <flux:link href="mailto:support@lotusmu.org">support@lotusmu.org</flux:link>
                    </li>
                    <li>
                        {{ __('Join our') }}
                        <flux:link href="{{ config('social.links.discord') }}">{{ __('Discord Server') }}</flux:link>
                        {{ __('for real-time support') }}
                    </li>
                    <li>
                        {{ __('Visit our') }}
                        <flux:link href="{{ route('support') }}" wire:navigate>{{ __('Help Center') }}</flux:link>
                        {{ __('for detailed assistance') }}
                    </li>
                </ul>
            </flux:text>
        </div>
    </div>
</flux:main>
