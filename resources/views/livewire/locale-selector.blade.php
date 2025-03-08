<?php

use App\Actions\Localization\SwitchLocale;
use Livewire\Volt\Component;

new class extends Component {
    public string $locale;
    public array $availableLocales;
    public string $triggerType = 'navbar'; // Options: 'navbar', 'navlist', 'button'

    // Language names mapping
    public array $localeNames = [
        'en' => 'English',
        'bg' => 'Bulgarian',
        'ru' => 'Russian',
    ];

    // Language flag image paths
    public array $localeFlags = [
        'en' => '/images/flags/1x1/gb.svg',
        'bg' => '/images/flags/1x1/bg.svg',
        'ru' => '/images/flags/1x1/ru.svg',
    ];

    public function mount(string $triggerType = 'navbar'): void
    {
        $this->locale           = app()->getLocale();
        $this->availableLocales = config('locales.available');
        $this->triggerType      = $triggerType;
    }

    public function setLocale(SwitchLocale $action, string $newLocale): void
    {
        $result = $action->handle(
            locale: $newLocale,
            referrer: request()->header('Referer')
        );

        if ($result['success']) {
            $this->locale = $result['locale'];

            // Show toast notification
            Flux::toast(
                text: __('Language has been changed successfully.'),
                heading: __('Language Changed'),
                variant: 'success'
            );

            $this->redirect($result['referrer'] ?? route('home'));
        }
    }
}

?>

<div>
    <flux:dropdown>
        {{-- Trigger dropdown --}}
        @if($triggerType === 'navbar')
            <flux:navbar.item icon-trailing="chevron-down">
                <span class="flex items-center gap-2">
                    @if(isset($localeFlags[$locale]))
                        <img src="{{ asset($localeFlags[$locale]) }}" alt="{{ $localeNames[$locale] ?? '' }} flag"
                             class="w-4 h-4 rounded-full">
                    @endif
                    <span>{{ $localeNames[$locale] ?? strtoupper($locale) }}</span>
                </span>
            </flux:navbar.item>
        @elseif($triggerType === 'navlist')
            <flux:navlist.item icon-trailing="chevron-down">
                <span class="flex items-center gap-2">
                    @if(isset($localeFlags[$locale]))
                        <img src="{{ asset($localeFlags[$locale]) }}" alt="{{ $localeNames[$locale] ?? '' }} flag"
                             class="w-4 h-4 rounded-full">
                    @endif
                    <span>{{ $localeNames[$locale] ?? strtoupper($locale) }}</span>
                </span>
            </flux:navlist.item>
        @else
            <flux:button variant="subtle" size="sm" icon-trailing="chevron-down" :tooltip="__('Change language')"
                         class="max-lg:hidden">
                <span class="flex items-center gap-2">
                    @if(isset($localeFlags[$locale]))
                        <img src="{{ asset($localeFlags[$locale]) }}" alt="{{ $localeNames[$locale] ?? '' }} flag"
                             class="w-4 h-4 rounded-full">
                    @endif
                    <span>{{ strtoupper($locale) }}</span>
                </span>
            </flux:button>
        @endif

        {{-- Dropdown menu --}}
        <flux:menu>
            <flux:menu.radio.group>
                @foreach($availableLocales as $availableLocale)
                    <flux:menu.radio
                        wire:key="{{ $availableLocale }}"
                        wire:click="setLocale('{{ $availableLocale }}')"
                        :checked="$locale === $availableLocale"
                    >
                        <div class="flex items-center justify-between w-full">
                            <span class="flex items-center gap-2">
                                @if(isset($localeFlags[$availableLocale]))
                                    <img src="{{ asset($localeFlags[$availableLocale]) }}"
                                         alt="{{ $localeNames[$availableLocale] ?? '' }} flag"
                                         class="w-4 h-4 rounded-full">
                                @endif
                                <span>{{ $localeNames[$availableLocale] ?? strtoupper($availableLocale) }}</span>
                            </span>
                        </div>
                    </flux:menu.radio>
                @endforeach
            </flux:menu.radio.group>
        </flux:menu>
    </flux:dropdown>
</div>
