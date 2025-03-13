<?php

use App\Actions\HandleDiscordInvitePopup;
use App\Actions\Localization\SwitchLocale;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Livewire\Volt\Component;

new class extends Component {
    public bool $show = false;
    public bool $neverShowAgain = false;

    public function mount()
    {
        $action     = new HandleDiscordInvitePopup();
        $this->show = $action->handle(Auth::user());
    }

    public function join()
    {
        $action = new HandleDiscordInvitePopup();
        $action->recordResponse(true, $this->neverShowAgain, Auth::user());
        $this->show = false;

        return redirect()->away(config('social.links.discord'));
    }

    public function decline(): void
    {
        $action = new HandleDiscordInvitePopup();
        $action->recordResponse(false, $this->neverShowAgain, Auth::user());
        $this->show = false;
    }

    public function close(): void
    {
        $action = new HandleDiscordInvitePopup();
        $action->recordResponse(false, $this->neverShowAgain, Auth::user());
        $this->show = false;
    }
}

?>

<div>
    @if($show)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-md mx-auto p-6">
                <div class="flex justify-between items-start">
                    <h2 class="text-xl font-bold text-gray-900">Join Our Discord Community!</h2>
                    <button wire:click="close" class="text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="mt-4">
                    <p class="text-gray-600">
                        Connect with our community, get help, and stay updated with the latest announcements!
                    </p>

                    <div class="mt-6 flex flex-col space-y-3">
                        <button wire:click="join"
                                class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                            Join Discord Server
                        </button>

                        <button wire:click="decline" class="text-gray-700 px-4 py-2 rounded-md hover:bg-gray-100">
                            Maybe Later
                        </button>

                        <label class="flex items-center mt-3">
                            <input type="checkbox" wire:model="neverShowAgain"
                                   class="rounded border-gray-300 text-indigo-600">
                            <span class="ml-2 text-sm text-gray-600">Don't show this again</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
