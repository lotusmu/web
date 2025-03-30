<flux:card class="space-y-6">
    <div>
        <flux:heading size="lg">
            {{ __('Frequently Asked Questions') }}
        </flux:heading>

        <flux:subheading>
            {{ __('Find quick solutions to common questions. If your issue isn\'t listed here, please') }}
            <flux:link :href="route('support')" wire:navigate.hover>
                {{ __('submit a support ticket.') }}
            </flux:link>
        </flux:subheading>
    </div>

    <flux:accordion transition exclusive>
        <flux:accordion.item>
            <flux:accordion.heading>{{ __('What are the minimum system requirements?') }}</flux:accordion.heading>

            <flux:accordion.content>
                <flux:table>
                    <flux:rows>
                        <flux:row>
                            <flux:cell>{{ __('Operating System') }}</flux:cell>
                            <flux:cell>{{ __('Windows 7 or newer') }}</flux:cell>
                        </flux:row>

                        <flux:row>
                            <flux:cell>{{ __('Processor') }}</flux:cell>
                            <flux:cell>{{ __('Pentium 4 – 2.0 Ghz or higher') }}</flux:cell>
                        </flux:row>

                        <flux:row>
                            <flux:cell>{{ __('System Memory') }}</flux:cell>
                            <flux:cell>{{ __('1 GB or higher') }}</flux:cell>
                        </flux:row>

                        <flux:row>
                            <flux:cell>{{ __('Video Card') }}</flux:cell>
                            <flux:cell>{{ __('3D graphics processor') }}</flux:cell>
                        </flux:row>

                        <flux:row>
                            <flux:cell>{{ __('DirectX Version') }}</flux:cell>
                            <flux:cell>{{ __('DirectX 9.0c or higher') }}</flux:cell>
                        </flux:row>

                        <flux:row>
                            <flux:cell>{{ __('Hard Disk Space') }}</flux:cell>
                            <flux:cell>{{ __('2GB or higher') }}</flux:cell>
                        </flux:row>
                    </flux:rows>
                </flux:table>
            </flux:accordion.content>
        </flux:accordion.item>

        <flux:accordion.item>
            <flux:accordion.heading>{{ __('How do I download and install the game?') }}</flux:accordion.heading>
            <flux:accordion.content>
                {{ __('1. Download the client from any of the provided mirrors above.') }}<br>
                {{ __('2. Run the installer and follow the instructions.') }}<br>
                {{ __('3. Make sure .NET Framework 3.5 is installed.') }}<br>
                {{ __('4. Run the main executable (Launcher.exe).') }}
            </flux:accordion.content>
        </flux:accordion.item>

        <flux:accordion.item>
            <flux:accordion.heading>{{ __('What should I do if the game won\'t start?') }}</flux:accordion.heading>
            <flux:accordion.content>
                {{ __('1. Verify .NET Framework 3.5 is installed correctly.') }}<br>
                {{ __('2. Run the game as Administrator.') }}<br>
                {{ __('3. Make sure all game files are extracted properly.') }}<br>
                {{ __('4. Run the resolution and language scripts located in the Scripts folder of the game client directory.') }}
                <br>
                {{ __('5. Check if your antivirus is blocking the game, as some antivirus software may incorrectly flag it as suspicious (false positive).') }}
                <br>
                {{ __('6. Update your DirectX and graphics drivers.') }} <br>
                {{ __("7. Make sure that the path to your game client does not include cyrilic words (i.e. C:\Игри\Lotus Mu).") }}
                <br><br>

                {{ __('If you cannot find a solution to your game performance issues here, please') }}
                {{ __('take a look at our advice in') }}
                <flux:link
                    href="https://wiki.lotusmu.org/troubleshooting"
                    external>
                    {{ __('this article.') }}
                </flux:link>
                
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
                    href="https://wiki.lotusmu.org/troubleshooting"
                    external>
                    {{ __('this article.') }}
                </flux:link>
            </flux:accordion.content>
        </flux:accordion.item>

        <flux:accordion.item>
            <flux:accordion.heading>{{ __('How do I report a bug or player?') }}</flux:accordion.heading>
            <flux:accordion.content>
                {{ __('1. Contact support through our website support form.') }}<br>
                {{ __('2. Include screenshots or video evidence when possible.') }}<br>
                {{ __('3. Provide detailed information about the issue or incident.') }}<br>
            </flux:accordion.content>
        </flux:accordion.item>

        <flux:accordion.item>
            <flux:accordion.heading>{{ __('Can I play on multiple accounts?') }}</flux:accordion.heading>
            <flux:accordion.content>
                {{ __('1. Yes, multiple accounts are allowed per player.') }}<br>
                {{ __('2. Each account requires a unique email address for verification.') }}<br>
                {{ __('3. Maximum of 3 game clients can be run simultaneously per user.') }}<br>
                {{ __('4. Each account must be logged in from a separate client.') }}<br>
            </flux:accordion.content>
        </flux:accordion.item>

        <flux:accordion.item>
            <flux:accordion.heading>{{ __('What are the server maintenance times?') }}</flux:accordion.heading>
            <flux:accordion.content>
                {{ __('1. Planned maintenance will be announced on our website and Discord server.') }}<br>
                {{ __('2. Emergency maintenance will be announced on our Discord.') }}<br>
                {{ __('3. Check our news channel for server status and maintenance updates.') }}<br>
                {{ __('4. Maintenance typically lasts 2-4 hours.') }}<br>
            </flux:accordion.content>
        </flux:accordion.item>

        <flux:accordion.item>
            <flux:accordion.heading>{{ __('Is the game free to play?') }}</flux:accordion.heading>
            <flux:accordion.content>
                {{ __('Yes, the game is completely free to play. We offer optional VIP and cash shop items that provide convenience but do not affect game balance.') }}
            </flux:accordion.content>
        </flux:accordion.item>

        <flux:accordion.item>
            <flux:accordion.heading>{{ __('How do I create an account?') }}</flux:accordion.heading>
            <flux:accordion.content>
                {{ __('You can create an account through our') }}
                <flux:link href="{{ route('register') }}">
                    {{ __('registration page') }}
                </flux:link>
                {{ __('.') }}
                {{ __('Make sure to use a valid email address as it will be needed for account verification and recovery.') }}
            </flux:accordion.content>
        </flux:accordion.item>

        <flux:accordion.item>
            <flux:accordion.heading>{{ __('Still having issues?') }}</flux:accordion.heading>
            <flux:accordion.content>
                {{ __('If you cannot find a solution to your problem here, please') }}
                <flux:link :href="route('support')" wire:navigate.hover>
                    {{ __('submit a support ticket') }}
                </flux:link>
                {{ __('and our team will assist you.') }}
            </flux:accordion.content>
        </flux:accordion.item>
    </flux:accordion>
</flux:card>
