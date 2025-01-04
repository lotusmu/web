<?php

use App\Models\Content\Download;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public function downloads(): Collection
    {
        return Download::query()
            ->latest()
            ->get()
            ->map(function ($download) {
                return [
                    'name' => $download->name,
                    'url'  => $download->file_url,
                    'icon' => $download->provider->getIcon(),
                ];
            });
    }
}; ?>

<flux:main container>
    <x-page-hero
        title="Begin your journey"
        kicker="Files"
        description="Ready to start your adventure? Download our game client and join the world of Lotus Mu."
    />

    <div class="space-y-6">
        <flux:card class="flex gap-2 items-center !bg-teal-50/50 dark:!bg-teal-400/20">
            <flux:icon.light-bulb/>
            <flux:text>
                <flux:link href="https://www.microsoft.com/en-us/download/details.aspx?id=25150"
                           external>{{ __('Microsoft .NET Framework 3.5') }}</flux:link>
                {{ __(' is required to run the game. Please install it before downloading the client.') }}
            </flux:text>
        </flux:card>

        <div class="grid grid-cols-3 max-md:grid-cols-1 gap-6">
            @foreach($this->downloads() as $download)
                <a href="{{ $download['url'] }}" target="_blank">
                    <flux:card
                        class="flex flex-1 items-center gap-4 w-full hover:bg-zinc-50 dark:hover:bg-white/5 transition-colors group">
                        <x-dynamic-component
                            :component="$download['icon']"
                            class="w-8 h-8"
                        />
                        <div>
                            <flux:heading>
                                {{ $download['name'] }}
                            </flux:heading>
                            <flux:subheading
                                class="group-hover:text-[var(--color-compliment-content)] transition-colors">
                                {{ __('Click to download') }}
                            </flux:subheading>
                        </div>
                    </flux:card>
                </a>
            @endforeach
        </div>

        <livewire:pages.guest.files.faq/>
    </div>
</flux:main>

