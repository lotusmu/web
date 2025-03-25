<flux:card class="space-y-6">
    <div>
        <flux:heading size="lg">
            {{ __('Frequently Asked Questions') }}
        </flux:heading>

        <flux:subheading>
            {{ __('Find quick solutions to common questions. If your issue isn\'t listed here, please') }}
            <flux:link :href="route('support.create-ticket')" wire:navigate.hover>
                {{ __('submit a ticket.') }}
            </flux:link>
        </flux:subheading>
    </div>

    <flux:accordion transition exclusive>
        <flux:accordion.item>
            <flux:accordion.heading>{{ __('How do I change my account password?') }}</flux:accordion.heading>
            <flux:accordion.content>
                {{ __('1. Go to your') }}
                <flux:link :href="route('profile', ['tab' => 'password'])" wire:navigate.hover variant="subtle">
                    {{ __('Profile Settings') }}
                </flux:link>
                <br>
                {{ __('2. Enter your current password for verification.') }}<br>
                {{ __('3. Enter and confirm your new password.') }}<br>
                {{ __('4. Save the changes to update your password.') }}
            </flux:accordion.content>
        </flux:accordion.item>

        <flux:accordion.item>
            <flux:accordion.heading>{{ __('How do I recover my account?') }}</flux:accordion.heading>
            <flux:accordion.content>
                {{ __('1. Click "Forgot Password" on the login page.') }}<br>
                {{ __('2. Enter your account email address.') }}<br>
                {{ __('3. Follow the recovery instructions sent to your email.') }}<br>
                {{ __('4. If you cannot access your email, contact support with proof of ownership.') }}<br>
                {{ __('5. Required proof includes original email, transaction records, or other account details.') }}
            </flux:accordion.content>
        </flux:accordion.item>

        <flux:accordion.item>
            <flux:accordion.heading>{{ __('How long does support ticket response take?') }}</flux:accordion.heading>
            <flux:accordion.content>
                {{ __('1. Standard tickets: Response within 24-48 hours.') }}<br>
                {{ __('2. Account security issues: Priority response within 12 hours.') }}<br>
                {{ __('3. Payment issues: Response within 24 hours.') }}<br>
                {{ __('4. General inquiries: Response within 48-72 hours.') }}
            </flux:accordion.content>
        </flux:accordion.item>

        <flux:accordion.item>
            <flux:accordion.heading>{{ __('My account was banned/blocked') }}</flux:accordion.heading>
            <flux:accordion.content>
                {{ __('1. Check your email for ban reason and duration.') }}<br>
                {{ __('2. Submit an appeal through the support system.') }}<br>
                {{ __('3. Provide any evidence supporting your appeal.') }}<br>
                {{ __('4. Appeals are typically reviewed within 48-72 hours.') }}<br>
                {{ __('5. Multiple violations may result in permanent account closure.') }}
            </flux:accordion.content>
        </flux:accordion.item>

        <flux:accordion.item>
            <flux:accordion.heading>{{ __('Payment Issues') }}</flux:accordion.heading>
            <flux:accordion.content>
                {{ __('1. Wait 15 minutes after payment for tokens to appear automatically.') }}<br>
                {{ __('2. If tokens do not appear, check your payment confirmation email.') }}<br>
                {{ __('3. For PayPal payments, verify the transaction status in your PayPal account.') }}<br>
                {{ __('4. For card payments, check if your bank approved the transaction.') }}<br>
                {{ __('5. Contact support if payment is confirmed but tokens are not received after 15 minutes.') }}
                <br><br>
                {{ __('Always save your payment confirmation/transaction ID when contacting support.') }}
            </flux:accordion.content>
        </flux:accordion.item>

        <flux:accordion.item>
            <flux:accordion.heading>{{ __('Why is my game performance low/laggy?') }}</flux:accordion.heading>
            <flux:accordion.content>
                {{ __('1. Update your graphics drivers to the latest version.') }}<br>
                {{ __('2. Lower in-game graphics settings.') }}<br>
                {{ __('3. Close unnecessary background applications.') }}<br>
                {{ __('4. Check your internet connection stability.') }}<br>
                {{ __('5. Verify your PC meets the minimum system requirements.') }}<br><br>

                {{ __('If you cannot find a solution to your game performance issues here, please') }}
                {{ __('take a look at our advice in') }}
                <flux:link
                    href="https://wiki.lotusmu.org/client-features/options-menu/#what-to-do-if-your-game-performance-is-lowlaggy"
                    external>
                    {{ __('this article.') }}
                </flux:link>
            </flux:accordion.content>
        </flux:accordion.item>

        <flux:accordion.item>
            <flux:accordion.heading>{{ __('How do I delete my account?') }}</flux:accordion.heading>
            <flux:accordion.content>
                {{ __('1. Submit an account deletion request.') }}<br>
                {{ __('2. Verify ownership.') }}<br>
                {{ __('3. Account data is retained for 30 days after deletion.') }}<br>
                {{ __('4. Deletion is permanent and cannot be reversed after 30 days.') }}<br>
            </flux:accordion.content>
        </flux:accordion.item>
    </flux:accordion>
</flux:card>
