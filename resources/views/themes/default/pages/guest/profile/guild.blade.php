<flux:main container>
    <flux:card class="max-w-2xl mx-auto space-y-8">
        @if($this->profile)
            <x-profile.guild.information :guild="$this->profile"/>
            <x-profile.guild.members :guild="$this->profile"/>
        @else
            <flux:text>{{ __('Guild not found or has been deleted.') }}</flux:text>
        @endif
    </flux:card>
</flux:main>