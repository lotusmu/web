<flux:card class="space-y-6">
    <div>
        <flux:heading size="lg">
            {{ __('Frequently Asked Questions') }}
        </flux:heading>

        <flux:subheading>
            {{ __('Find quick solutions to common questions.') }}
        </flux:subheading>
    </div>

    <flux:accordion transition>
        <flux:accordion.item>
            <flux:accordion.heading>What's your refund policy?</flux:accordion.heading>

            <flux:accordion.content>
                If you are not satisfied with your purchase, we offer a 30-day money-back guarantee. Please contact
                our
                support team for assistance.
            </flux:accordion.content>
        </flux:accordion.item>

        <flux:accordion.item>
            <flux:accordion.heading>Do you offer any discounts for bulk purchases?</flux:accordion.heading>

            <flux:accordion.content>
                Yes, we offer special discounts for bulk orders. Please reach out to our sales team with your
                requirements.
            </flux:accordion.content>
        </flux:accordion.item>

        <flux:accordion.item>
            <flux:accordion.heading>How do I track my order?</flux:accordion.heading>

            <flux:accordion.content>
                Once your order is shipped, you will receive an email with a tracking number. Use this number to
                track
                your order on our website.
            </flux:accordion.content>
        </flux:accordion.item>
    </flux:accordion>
</flux:card>
