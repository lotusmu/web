<flux:main container>
    <div class="pt-16 mx-auto max-w-3xl space-y-12">
        <div>
            <flux:heading size="xl">
                {{ __('Refund Policy') }}
            </flux:heading>
            <flux:text size="sm" class="mt-2">
                {{ __('Last Updated: July 2025') }}
            </flux:text>
        </div>

        <div class="space-y-2">
            <flux:heading size="lg">
                {{ __('1. General Refund Policy') }}
            </flux:heading>

            <flux:text>
                {{ __('ALL SALES ARE FINAL. Virtual currency and digital items are delivered immediately upon purchase confirmation. As a general rule, no refunds are provided for virtual goods and digital content.') }}
            </flux:text>

            <flux:text>
                {{ __('The price displayed for each package is the final amount you will pay. We do not charge additional processing fees, subscription fees, or hidden charges. All applicable taxes are included in the displayed price.') }}
            </flux:text>
        </div>

        <div class="space-y-2">
            <flux:heading size="lg">
                {{ __('2. EU Consumer Rights - Limited Exceptions') }}
            </flux:heading>

            <flux:text>
                {{ __('In compliance with EU consumer protection laws, refunds may be considered only under the following strict conditions:') }}
            </flux:text>

            <flux:heading>
                {{ __('2.1 Eligibility Requirements') }}
            </flux:heading>

            <flux:text>
                <ul class="list-disc pl-6 space-y-2">
                    <li>{{ __('Request made within 14 days of purchase') }}</li>
                    <li>{{ __('Virtual currency remains completely unused in your account') }}</li>
                    <li>{{ __('Account has sufficient balance to reverse the transaction entirely') }}</li>
                    <li>{{ __('Account in good standing with no history of violations') }}</li>
                    <li>{{ __('Payment method verified as legitimate and authorized') }}</li>
                </ul>
            </flux:text>

            <flux:heading>
                {{ __('2.2 Fraud Prevention Verification') }}
            </flux:heading>

            <flux:text>
                {{ __('Before processing any refund request, we will verify:') }}
            </flux:text>

            <flux:text>
                <ul class="list-disc pl-6 space-y-2">
                    <li>{{ __('Payment method ownership and authorization') }}</li>
                    <li>{{ __('Account activity and transaction history') }}</li>
                    <li>{{ __('Absence of suspicious or fraudulent activity') }}</li>
                    <li>{{ __('Compliance with all Terms of Service') }}</li>
                </ul>
            </flux:text>
        </div>

        <div class="space-y-2">
            <flux:heading size="lg">
                {{ __('3. Refund Exclusions') }}
            </flux:heading>

            <flux:text>
                {{ __('Refunds are NOT available under any circumstances for:') }}
            </flux:text>

            <flux:heading>
                {{ __('3.1 Used Virtual Goods') }}
            </flux:heading>

            <flux:text>
                <ul class="list-disc pl-6 space-y-2">
                    <li>{{ __('Partially or fully spent virtual currency') }}</li>
                    <li>{{ __('Virtual items that have been used, equipped, or consumed') }}</li>
                    <li>{{ __('Any virtual goods that have affected gameplay or account progression') }}</li>
                </ul>
            </flux:text>

            <flux:heading>
                {{ __('3.2 Fraudulent or Suspicious Activity') }}
            </flux:heading>

            <flux:text>
                <ul class="list-disc pl-6 space-y-2">
                    <li>{{ __('Purchases made with stolen or unauthorized payment methods') }}</li>
                    <li>{{ __('Accounts with chargeback history or payment disputes') }}</li>
                    <li>{{ __('Suspected refund abuse or manipulation attempts') }}</li>
                    <li>
                        {{ __('Accounts involved in') }}
                        <flux:link href="{{ route('terms') }}" wire:navigate>{{ __('Terms of Service') }}</flux:link>
                        {{ __('violations') }}
                    </li>
                </ul>
            </flux:text>

            <flux:heading>
                {{ __('3.3 Technical Issues') }}
            </flux:heading>

            <flux:text>
                <ul class="list-disc pl-6 space-y-2">
                    <li>{{ __('Account suspensions due to rule violations') }}</li>
                    <li>{{ __('Service interruptions or maintenance periods') }}</li>
                    <li>{{ __('User error or accidental purchases (buyers\' remorse)') }}</li>
                    <li>{{ __('Changes to virtual goods or service features') }}</li>
                </ul>
            </flux:text>
        </div>

        <div class="space-y-2">
            <flux:heading size="lg">
                {{ __('4. Withdrawal of Right to Refund') }}
            </flux:heading>

            <flux:text>
                {{ __('IMPORTANT: By purchasing virtual goods, you acknowledge that:') }}
            </flux:text>

            <flux:text>
                <ul class="list-disc pl-6 space-y-2">
                    <li>{{ __('Digital content is delivered immediately upon purchase') }}</li>
                    <li>{{ __('You expressly request immediate access to virtual goods') }}</li>
                    <li>{{ __('You waive your right of withdrawal under EU consumer law') }}</li>
                    <li>{{ __('Virtual goods are consumed upon delivery to your account') }}</li>
                </ul>
            </flux:text>
        </div>

        <div class="space-y-2">
            <flux:heading size="lg">
                {{ __('5. Chargeback Protection') }}
            </flux:heading>

            <flux:heading>
                {{ __('5.1 Chargeback Policy') }}
            </flux:heading>

            <flux:text>
                {{ __('Filing chargebacks for legitimately received virtual goods is strictly prohibited and constitutes fraud.') }}
            </flux:text>

            <flux:heading>
                {{ __('5.2 Consequences of Illegitimate Chargebacks') }}
            </flux:heading>

            <flux:text>
                <ul class="list-disc pl-6 space-y-2">
                    <li>{{ __('Immediate account termination') }}</li>
                    <li>{{ __('Revocation of all virtual goods') }}</li>
                    <li>{{ __('Permanent service ban') }}</li>
                    <li>{{ __('Reporting to payment processors and authorities') }}</li>
                    <li>{{ __('Legal action to recover damages and fees') }}</li>
                </ul>
            </flux:text>

            <flux:heading>
                {{ __('5.3 Chargeback Investigation') }}
            </flux:heading>

            <flux:text>
                {{ __('We maintain comprehensive transaction records and will contest all illegitimate chargebacks. You acknowledge that disputing legitimate charges may violate payment processor terms and applicable fraud laws.') }}
            </flux:text>
        </div>

        <div class="space-y-2">
            <flux:heading size="lg">
                {{ __('6. Fraud Detection and Prevention') }}
            </flux:heading>

            <flux:heading>
                {{ __('6.1 Payment Verification') }}
            </flux:heading>

            <flux:text>
                {{ __('We reserve the right to:') }}
            </flux:text>

            <flux:text>
                <ul class="list-disc pl-6 space-y-2">
                    <li>{{ __('Verify payment method ownership before processing refunds') }}</li>
                    <li>{{ __('Request additional documentation or verification') }}</li>
                    <li>{{ __('Delay or deny refunds pending fraud investigation') }}</li>
                    <li>{{ __('Report suspected fraud to relevant authorities') }}</li>
                </ul>
            </flux:text>

            <flux:heading>
                {{ __('6.2 Fraudulent Payment Consequences') }}
            </flux:heading>

            <flux:text>
                {{ __('If fraud is detected:') }}
            </flux:text>

            <flux:text>
                <ul class="list-disc pl-6 space-y-2">
                    <li>{{ __('All virtual goods are immediately revoked') }}</li>
                    <li>{{ __('Account is permanently suspended') }}</li>
                    <li>{{ __('No refunds or compensation provided') }}</li>
                    <li>{{ __('Full cooperation with law enforcement and payment processors') }}</li>
                </ul>
            </flux:text>
        </div>

        <div class="space-y-2">
            <flux:heading size="lg">
                {{ __('7. Alternative Dispute Resolution') }}
            </flux:heading>

            <flux:heading>
                {{ __('7.1 Support First Policy') }}
            </flux:heading>

            <flux:text>
                {{ __('Before initiating any payment dispute, you must:') }}
            </flux:text>

            <flux:text>
                <ul class="list-disc pl-6 space-y-2">
                    <li>{{ __('Contact our support team within 48 hours of the transaction') }}</li>
                    <li>{{ __('Provide detailed explanation of the issue') }}</li>
                    <li>{{ __('Allow reasonable time for investigation and resolution') }}</li>
                    <li>{{ __('Exhaust all support channels before external disputes') }}</li>
                </ul>
            </flux:text>

            <flux:heading>
                {{ __('7.2 EU Online Dispute Resolution') }}
            </flux:heading>

            <flux:text>
                {{ __('EU customers may access the EU Online Dispute Resolution platform at') }}
                <flux:link href="http://ec.europa.eu/consumers/odr" target="_blank">
                    http://ec.europa.eu/consumers/odr.
                </flux:link>
            </flux:text>
        </div>

        <div class="space-y-2">
            <flux:heading size="lg">
                {{ __('8. Refund Process') }}
            </flux:heading>

            <flux:heading>
                {{ __('8.1 How to Request a Refund') }}
            </flux:heading>

            <flux:text>
                <ul class="list-disc pl-6 space-y-2">
                    <li>{{ __('Submit a support ticket within 14 days of purchase') }}</li>
                    <li>{{ __('Provide proof of purchase and account verification') }}</li>
                    <li>{{ __('Clearly explain the reason for the refund request') }}</li>
                    <li>{{ __('Allow up to 10 business days for investigation') }}</li>
                </ul>
            </flux:text>

            <flux:heading>
                {{ __('8.2 Verification Requirements') }}
            </flux:heading>

            <flux:text>
                {{ __('All refund requests require:') }}
            </flux:text>

            <flux:text>
                <ul class="list-disc pl-6 space-y-2">
                    <li>{{ __('Identity verification') }}</li>
                    <li>{{ __('Payment method verification') }}</li>
                    <li>{{ __('Account activity review') }}</li>
                    <li>{{ __('Fraud prevention screening') }}</li>
                </ul>
            </flux:text>

            <flux:heading>
                {{ __('8.3 Processing Time') }}
            </flux:heading>

            <flux:text>
                <ul class="list-disc pl-6 space-y-2">
                    <li>{{ __('Investigation: Up to 10 business days') }}</li>
                    <li>{{ __('Approved refunds: 5-10 business days to original payment method') }}</li>
                    <li>{{ __('Denied refunds: Written explanation provided') }}</li>
                </ul>
            </flux:text>
        </div>

        <div class="space-y-2">
            <flux:heading size="lg">
                {{ __('9. Payment Processor Compliance') }}
            </flux:heading>

            <flux:text>
                {{ __('This policy complies with:') }}
            </flux:text>

            <flux:text>
                <ul class="list-disc pl-6 space-y-2">
                    <li>{{ __('PayPal Acceptable Use Policy') }}</li>
                    <li>{{ __('Stripe Terms of Service') }}</li>
                    <li>{{ __('EU Payment Services Directive') }}</li>
                    <li>{{ __('Bulgarian consumer protection laws') }}</li>
                </ul>
            </flux:text>
        </div>

        <div class="space-y-2">
            <flux:heading size="lg">
                {{ __('10. No Partial Refunds') }}
            </flux:heading>

            <flux:text>
                {{ __('We do not provide partial refunds. Refunds, when approved, are processed in full to the original payment method only.') }}
            </flux:text>
        </div>

        <div class="space-y-2">
            <flux:heading size="lg">
                {{ __('11. Currency and Conversion') }}
            </flux:heading>

            <flux:text>
                <ul class="list-disc pl-6 space-y-2">
                    <li>{{ __('All refunds processed in original transaction currency') }}</li>
                    <li>{{ __('No compensation for currency conversion fluctuations') }}</li>
                    <li>{{ __('Exchange rate differences are not grounds for refund adjustment') }}</li>
                </ul>
            </flux:text>
        </div>

        <div class="space-y-2">
            <flux:heading size="lg">
                {{ __('12. Account Closure and Refunds') }}
            </flux:heading>

            <flux:text>
                {{ __('No refunds are provided for virtual goods when:') }}
            </flux:text>

            <flux:text>
                <ul class="list-disc pl-6 space-y-2">
                    <li>{{ __('Account is terminated for Terms of Service violations') }}</li>
                    <li>{{ __('Account is closed due to fraudulent activity') }}</li>
                    <li>{{ __('User voluntarily closes account after using virtual goods') }}</li>
                </ul>
            </flux:text>
        </div>

        <div class="space-y-2">
            <flux:heading size="lg">
                {{ __('13. Legal Compliance') }}
            </flux:heading>

            <flux:text>
                {{ __('This policy is designed to comply with:') }}
            </flux:text>

            <flux:text>
                <ul class="list-disc pl-6 space-y-2">
                    <li>{{ __('EU Consumer Rights Directive') }}</li>
                    <li>{{ __('Bulgarian consumer protection laws') }}</li>
                    <li>{{ __('Payment processor requirements') }}</li>
                    <li>{{ __('Anti-fraud regulations') }}</li>
                </ul>
            </flux:text>
        </div>

        <div class="space-y-2">
            <flux:heading size="lg">
                {{ __('14. Shipping and Delivery') }}
            </flux:heading>

            <flux:text>
                {{ __('We exclusively provide digital content and virtual goods. These are delivered automatically upon successful payment confirmation. No physical items are shipped.') }}
            </flux:text>
        </div>

        <div class="space-y-2">
            <flux:heading size="lg">
                {{ __('15. Currency and Payment Processing') }}
            </flux:heading>

            <flux:text>
                {{ __('All transactions are processed in Euros (€). Any currency conversion is handled by our payment processors. We do not charge additional conversion fees beyond what is stated in our packages.') }}
            </flux:text>
        </div>

        <div class="space-y-2">
            <flux:heading size="lg">
                {{ __('16. Policy Updates') }}
            </flux:heading>

            <flux:text>
                {{ __('We may update this policy at any time. Material changes will be communicated via email or service notification. Continued use after updates constitutes acceptance of the revised policy.') }}
            </flux:text>
        </div>

        <div class="space-y-2">
            <flux:heading size="lg">
                {{ __('17. Contact Information') }}
            </flux:heading>

            <flux:text>
                {{ __('For refund requests or payment-related questions, you can:') }}
            </flux:text>

            <flux:text>
                <ul class="list-disc pl-6 space-y-2">
                    <li>
                        {{ __('Email our billing team at') }}
                        <flux:link href="mailto:support@yulanmu.com">support@yulanmu.com</flux:link>
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

        <div class="space-y-2">
            <flux:heading size="lg">
                {{ __('18. Important Notice') }}
            </flux:heading>

            <flux:text>
                {{ __('Include your transaction ID, account information, and detailed explanation when requesting a refund. All refund requests are subject to fraud verification and may be denied if fraudulent activity is suspected.') }}
            </flux:text>
        </div>
    </div>
</flux:main>
