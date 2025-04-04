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
                {{ __('Refund Policy') }}
            </flux:heading>
            <flux:text size="sm" class="mt-2">
                {{ __('Last Updated: January 2025') }}
            </flux:text>
        </div>

        <div class="space-y-2">
            <flux:heading size="lg">
                {{ __('Virtual Currency and Products') }}
            </flux:heading>

            <flux:text>
                {{ __('Our website offers virtual currency and items for use within our Mu Online game. These are classified as digital content and virtual products. While we aim to provide the best service possible, specific conditions apply to refunds of virtual currency and items.') }}
            </flux:text>

            <flux:text>
                {{ __('The price displayed for each package is the final amount you will pay. We do not charge additional processing fees, subscription fees, or hidden charges. All applicable taxes are included in the displayed price.') }}
            </flux:text>
        </div>

        <div class="space-y-2">
            <flux:heading size="lg">
                {{ __('Refund Eligibility') }}
            </flux:heading>

            <flux:text>
                {{ __('As per EU consumer protection laws, you have the right to request a refund within 14 days of purchase under the following conditions:') }}
            </flux:text>

            <flux:text>
                <ul class="list-disc pl-6 space-y-2">
                    <li>{{ __('The virtual currency remains completely unused in your account') }}</li>
                    <li>{{ __('Your account has sufficient balance to reverse the transaction completely') }}</li>
                    <li>{{ __('The request is made within 14 days of the original purchase') }}</li>
                    <li>{{ __('Your account is in good standing with no history of fraudulent activity') }}</li>
                </ul>
            </flux:text>
        </div>

        <div class="space-y-2">
            <flux:heading size="lg">
                {{ __('Refund Restrictions') }}
            </flux:heading>

            <flux:text>
                {{ __('Refunds will not be available under the following circumstances:') }}
            </flux:text>

            <flux:text>
                <ul class="list-disc pl-6 space-y-2">
                    <li>{{ __('The virtual currency has been partially or fully spent within the game') }}</li>
                    <li>
                        {{ __('The account has been involved in any violation of our') }}
                        <flux:link href="{{ route('terms') }}" wire:navigate>{{ __('Terms of Service') }}</flux:link>
                    </li>
                    <li>{{ __('The purchase was made more than 14 days ago') }}</li>
                    <li>{{ __('The account shows patterns of refund abuse or suspicious activity') }}</li>
                </ul>
            </flux:text>
        </div>

        <div class="space-y-2">
            <flux:heading size="lg">
                {{ __('Right of Withdrawal') }}
            </flux:heading>

            <flux:text>
                {{ __('In accordance with EU consumer protection laws, you have the right to withdraw from this contract within 14 days without giving any reason. However, you acknowledge that by requesting immediate access to the digital content and beginning to download or use the virtual goods, you expressly waive your right of withdrawal.') }}
            </flux:text>
        </div>

        <div class="space-y-2">
            <flux:heading size="lg">
                {{ __('Prohibited Actions') }}
            </flux:heading>

            <flux:text>
                {{ __('The following actions are strictly prohibited and may result in legal action and permanent account termination:') }}
            </flux:text>

            <flux:text>
                <ul class="list-disc pl-6 space-y-2">
                    <li>{{ __('Filing false chargeback claims after receiving and using virtual goods') }}</li>
                    <li>{{ __('Making fraudulent payment disputes') }}</li>
                    <li>{{ __('Attempting to reverse payments after using virtual goods') }}</li>
                    <li>{{ __('Engaging in any form of payment manipulation or fraud') }}</li>
                </ul>
            </flux:text>
        </div>

        <div class="space-y-2">
            <flux:heading size="lg">
                {{ __('Shipping and Delivery') }}
            </flux:heading>

            <flux:text>
                {{ __('We exclusively provide digital content and virtual goods. These are delivered automatically upon successful payment confirmation. No physical items are shipped.') }}
            </flux:text>
        </div>

        <div class="space-y-2">
            <flux:heading size="lg">
                {{ __('Dispute Resolution') }}
            </flux:heading>

            <flux:text>
                {{ __('We encourage you to contact our support team before initiating any payment disputes. EU customers have the right to use the EU Online Dispute Resolution platform at') }}
                <flux:link href="http://ec.europa.eu/consumers/odr" target="_blank">
                    http://ec.europa.eu/consumers/odr.
                </flux:link>
            </flux:text>

            <flux:text>
                {{ __('In case of a payment dispute, our support team will investigate within 5 business days. We require proof of purchase and account information. All payment dispute communications must be in writing and submitted through our support system for proper tracking. You may also use the dispute resolution tools provided by PayPal or other payment processors, but we encourage contacting our support team first to resolve issues more quickly.') }}
            </flux:text>
        </div>

        <div class="space-y-2">
            <flux:heading size="lg">
                {{ __('Currency and Payment Processing') }}
            </flux:heading>

            <flux:text>
                {{ __('All transactions are processed in Euros (€). Any currency conversion is handled by our payment processors. We do not charge additional conversion fees beyond what is stated in our packages.') }}
            </flux:text>
        </div>

        <div class="space-y-2">
            <flux:heading size="lg">
                {{ __('Contact Information') }}
            </flux:heading>

            <flux:text>
                {{ __('For refund requests or payment-related questions, you can:') }}
            </flux:text>

            <flux:text>
                <ul class="list-disc pl-6 space-y-2">
                    <li>
                        {{ __('Email our billing team at') }}
                        <flux:link href="mailto:support@lotusmu.org">support@lotusmu.org</flux:link>
                    </li>
                    <li>
                        {{ __('Get instant support on our') }}
                        <flux:link href="{{ config('social.links.discord') }}">{{ __('Discord Server') }}</flux:link>
                    </li>
                    <li>
                        {{ __('Open a support ticket in our') }}
                        <flux:link href="{{ route('support') }}" wire:navigate>{{ __('Help Center') }}</flux:link>
                    </li>
                </ul>
            </flux:text>
        </div>
    </div>
</flux:main>
