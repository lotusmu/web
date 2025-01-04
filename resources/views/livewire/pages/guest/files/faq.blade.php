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
            <flux:accordion.heading>What are the minimum system requirements?</flux:accordion.heading>

            <flux:accordion.content>
                <flux:table>
                    <flux:rows>
                        <flux:row>
                            <flux:cell>Operating System</flux:cell>
                            <flux:cell>Windows 7</flux:cell>
                        </flux:row>

                        <flux:row>
                            <flux:cell>Processor</flux:cell>
                            <flux:cell>Pentium 4 – 2.0 Ghz or higher</flux:cell>
                        </flux:row>

                        <flux:row>
                            <flux:cell>System Memory</flux:cell>
                            <flux:cell>1 GB or higher</flux:cell>
                        </flux:row>

                        <flux:row>
                            <flux:cell>Video Card</flux:cell>
                            <flux:cell>3D graphics processor</flux:cell>
                        </flux:row>

                        <flux:row>
                            <flux:cell>DirectX Version</flux:cell>
                            <flux:cell>DirectX 9.0c or higher</flux:cell>
                        </flux:row>

                        <flux:row>
                            <flux:cell>Hard Disk Space</flux:cell>
                            <flux:cell>2GB or higher</flux:cell>
                        </flux:row>
                    </flux:rows>
                </flux:table>
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
