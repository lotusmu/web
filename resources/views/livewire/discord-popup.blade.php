<?php

use App\Actions\HandleDiscordInvitePopup;
use App\Actions\Localization\SwitchLocale;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Livewire\Volt\Component;


new class extends Component {
    public bool $neverShowAgain = false;

    public function mount(HandleDiscordInvitePopup $action)
    {
        $shouldShow = $action->shouldShow(Auth::user());

        if ($shouldShow) {
            $this->dispatch('show-discord-modal');
        }
    }

    public function joinDiscord(HandleDiscordInvitePopup $action)
    {
        $action->recordResponse(true, $this->neverShowAgain, Auth::user());

        Flux::modal('discord-invitation')->close();
    }

    public function declineDiscord(HandleDiscordInvitePopup $action)
    {
        $action->recordResponse(false, $this->neverShowAgain, Auth::user());
    }
}

?>

<div>
    <flux:modal name="discord-invitation" :dismissible="false" @close="declineDiscord">
        <div class="space-y-6">
            <div class="flex items-start space-x-8">
                <div class="flex justify-center max-w-20">
                    <img src="{{ asset('images/discord.png') }}" alt="{{ __('Discord Brand Logo in a 3D image') }}">
                </div>
                <div>
                    <flux:heading size="lg">
                        {{ __('Join Our Discord Community!') }}
                    </flux:heading>

                    <flux:subheading>
                        {{ __('Connect with our community, get help, and stay updated with the latest announcements!') }}
                    </flux:subheading>
                </div>
            </div>

            <div class="flex max-sm:flex-col-reverse items-center gap-2">
                <div class="max-sm:w-full">
                    <flux:checkbox wire:model="neverShowAgain" label="{{ __('Don\'t show this again') }}"/>
                </div>

                <flux:spacer/>

                <div class="max-sm:w-full">
                    <flux:modal.close>
                        <flux:button variant="ghost" wire:click="declineDiscord"
                                     class="w-full">
                            {{ __("I'll do it later") }}
                        </flux:button>
                    </flux:modal.close>
                </div>

                <div class="max-sm:w-full">
                    <flux:button variant="primary"
                                 target="_blank"
                                 icon-trailing="arrow-long-right"
                                 :href="config('social.links.discord')"
                                 wire:click="joinDiscord"
                                 class="w-full">
                        {{ __('Join Discord') }}
                    </flux:button>
                </div>
            </div>
        </div>
    </flux:modal>
</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('show-discord-modal', () => {
            const randomDelay = 500 + Math.floor(Math.random() * 200);
            setTimeout(() => {
                Flux.modal('discord-invitation').show()
            }, randomDelay);
        });
    });
</script>
