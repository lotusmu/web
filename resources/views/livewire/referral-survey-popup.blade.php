<?php

namespace App\Livewire;

use App\Actions\HandleReferralSurvey;
use App\Enums\Survey\ReferralSource;
use App\Enums\Survey\MMOTopSite;
use App\Enums\Survey\MUOnlineForum;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Volt\Component;

new class extends Component {
    public ?string $referralSource = null;
    public ?string $mmoTopSite = null;
    public ?string $muOnlineForum = null;
    public ?string $customSource = null;

    protected function rules(): array
    {
        return [
            'referralSource' => [
                'required',
                'string',
                'in:'.collect(ReferralSource::cases())->pluck('value')->implode(',')
            ],
            'mmoTopSite'     => [
                'required_if:referralSource,'.ReferralSource::MMOTopSite->value,
                'nullable',
                'string',
                'in:'.collect(MMOTopSite::cases())->pluck('value')->implode(',')
            ],
            'muOnlineForum'  => [
                'required_if:referralSource,'.ReferralSource::MUOnlineForum->value,
                'nullable',
                'string',
                'in:'.collect(MUOnlineForum::cases())->pluck('value')->implode(',')
            ],
            'customSource'   => [
                'required_if:referralSource,'.ReferralSource::Other->value,
                'nullable',
                'string',
                'max:255'
            ],
        ];
    }

    protected function messages(): array
    {
        return [
            'referralSource.required'   => __('Please select a referral source'),
            'mmoTopSite.required_if'    => __('Please select an MMO Top Site'),
            'muOnlineForum.required_if' => __('Please select a MU Online Forum'),
            'customSource.required_if'  => __('Please specify the source'),
        ];
    }

    public function mount(): void
    {
        if (Session::pull('show_referral_survey', false)) {
            $this->dispatch('show-referral-survey-modal');
        }
    }

    public function submitSurvey(HandleReferralSurvey $action): void
    {
        $user = Auth::user();

        if ( ! $user) {
            return;
        }

        $this->validate();

        $action->recordResponse(
            $user,
            $this->referralSource,
            $this->mmoTopSite,
            $this->muOnlineForum,
            $this->customSource,
            false // not dismissed
        );

        Flux::modal('referral-survey')->close();

        Flux::toast(
            text: __('Thank you for letting us know how you found us!'),
            heading: __('Success'),
            variant: 'success'
        );
    }

    public function dismissSurvey(HandleReferralSurvey $action): void
    {
        $user = Auth::user();

        if ( ! $user) {
            return;
        }

        $action->recordResponse(
            $user,
            null,
            null,
            null,
            null,
            true // dismissed
        );

        Flux::modal('referral-survey')->close();
    }

    public function showMMOTopSiteField(): bool
    {
        return $this->referralSource === ReferralSource::MMOTopSite->value;
    }

    public function showMUOnlineForumField(): bool
    {
        return $this->referralSource === ReferralSource::MUOnlineForum->value;
    }

    public function showCustomSourceField(): bool
    {
        return $this->referralSource === ReferralSource::Other->value;
    }
}; ?>

<div>
    <flux:modal name="referral-survey" :dismissible="false" :closable="false"
                class="relative border-0">
        <div
            class="absolute top-0 right-0 left-0 h-1 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500"></div>

        <form wire:submit="submitSurvey">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">
                        {{ __('How did you find out about us?') }}
                    </flux:heading>

                    <flux:subheading>
                        {{ __('Your feedback helps us understand how players discover us.') }}
                    </flux:subheading>
                </div>

                <div class="space-y-4">
                    <div>
                        <flux:select
                            variant="listbox"
                            wire:model.live="referralSource"
                            label="{{ __('Select one') }}"
                            placeholder="{{ __('Please select...') }}"
                            class="flex-col">
                            @foreach(ReferralSource::cases() as $source)
                                <flux:option value="{{ $source->value }}">
                                    {{ $source->getLabel() }}
                                </flux:option>
                            @endforeach
                        </flux:select>
                    </div>

                    @if($this->showMMOTopSiteField())
                        <div>
                            <flux:radio.group wire:model="mmoTopSite" label="{{ __('Which MMO Listing Site?') }}">
                                @foreach(MMOTopSite::cases() as $site)
                                    <flux:radio value="{{ $site->value }}" label="{{ $site->getLabel() }}"/>
                                @endforeach
                            </flux:radio.group>
                        </div>
                    @endif

                    @if($this->showMUOnlineForumField())
                        <div>
                            <flux:radio.group wire:model="muOnlineForum" label="{{ __('Which MU Online Forum?') }}">
                                @foreach(MUOnlineForum::cases() as $forum)
                                    <flux:radio value="{{ $forum->value }}" label="{{ $forum->getLabel() }}"/>
                                @endforeach
                            </flux:radio.group>
                        </div>
                    @endif

                    @if($this->showCustomSourceField())
                        <div>
                            <flux:input
                                wire:model="customSource"
                                label="{{ __('Please specify') }}"
                            />
                        </div>
                    @endif
                </div>

                <div class="flex max-sm:flex-col-reverse items-center gap-2">
                    <flux:spacer/>

                    <div class="max-sm:w-full">
                        <flux:button variant="ghost" wire:click="dismissSurvey"
                                     class="w-full">
                            {{ __('No thanks') }}
                        </flux:button>
                    </div>

                    <div class="max-sm:w-full">
                        <flux:button variant="primary"
                                     type="submit"
                                     class="w-full">
                            {{ __('Submit') }}
                        </flux:button>
                    </div>
                </div>
            </div>
        </form>
    </flux:modal>
</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('show-referral-survey-modal', () => {
            setTimeout(() => {
                Flux.modal('referral-survey').show()
            }, 1000);
        });
    });
</script>
