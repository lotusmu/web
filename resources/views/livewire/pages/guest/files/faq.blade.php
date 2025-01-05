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
                {{ __('1. Download the client from any of the provided mirrors above') }}<br>
                {{ __('2. Extract the files using WinRAR or similar program') }}<br>
                {{ __('3. Make sure .NET Framework 3.5 is installed') }}<br>
                {{ __('4. Run the main executable (Launcher.exe)') }}
            </flux:accordion.content>
        </flux:accordion.item>

        <flux:accordion.item>
            <flux:accordion.heading>{{ __('What should I do if the game won\'t start?') }}</flux:accordion.heading>
            <flux:accordion.content>
                {{ __('1. Verify .NET Framework 3.5 is installed correctly') }}<br>
                {{ __('2. Run the game as Administrator') }}<br>
                {{ __('3. Check if your antivirus is blocking the game') }}<br>
                {{ __('4. Make sure all game files are extracted properly') }}<br>
                {{ __('5. Update your DirectX and graphics drivers') }}
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
    </flux:accordion>
</flux:card>
