<?php

use App\Actions\HandleDiscordInvitePopup;
use App\Actions\Localization\SwitchLocale;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Livewire\Volt\Component;


new class extends Component {
    public bool $neverShowAgain = false;

    public function mount()
    {
        $action     = new HandleDiscordInvitePopup();
        $shouldShow = $action->handle(Auth::user());


        if ($shouldShow) {
            $this->dispatch('show-discord-modal');
        }
    }

    public function joinDiscord()
    {
        $action = new HandleDiscordInvitePopup();
        $action->recordResponse(true, $this->neverShowAgain, Auth::user());
    }

    public function declineDiscord()
    {
        $action = new HandleDiscordInvitePopup();
        $action->recordResponse(false, $this->neverShowAgain, Auth::user());
    }
}

?>

<div>
    <flux:modal name="discord-invitation" :dismissible="false" @close="declineDiscord">
        <div class="space-y-6">
            <div class="flex items-start space-x-8">
                <div class="flex justify-center max-w-20">
                    <img src="{{ asset('images/discord.png') }}" alt="Discord Brand Logo in a 3D image">
                </div>
                <div>
                    <flux:heading size="lg">
                        Join Our Discord Community!
                    </flux:heading>

                    <flux:subheading>
                        Connect with our community, get help, and stay updated with the latest
                        announcements!
                    </flux:subheading>
                </div>
            </div>

            <div class="flex max-sm:flex-col-reverse items-center gap-2">
                <div class="max-sm:w-full">
                    <flux:checkbox wire:model="neverShowAgain" label="Don't show this again"/>
                </div>

                <flux:spacer/>

                <div class="max-sm:w-full">
                    <flux:modal.close>
                        <flux:button variant="ghost" wire:click="declineDiscord"
                                     class="w-full">
                            I'll do it later
                        </flux:button>
                    </flux:modal.close>
                </div>

                <div class="max-sm:w-full">
                    <flux:button variant="primary"
                                 external
                                 icon-trailing="arrow-long-right"
                                 :href="config('social.links.discord')"
                                 wire:click="joinDiscord"
                                 class="w-full">
                        Join Discord
                    </flux:button>
                </div>
            </div>
        </div>
    </flux:modal>
</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('show-discord-modal', () => {
            setTimeout(() => {
                Flux.modal('discord-invitation').show()
            }, 300);
        });
    });
</script>
