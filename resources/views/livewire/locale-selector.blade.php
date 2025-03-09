<?php

use App\Actions\Localization\SwitchLocale;
use Livewire\Volt\Component;

new class extends Component {
    // Default values assigned directly to properties
    public string $locale;
    public array $availableLocales = [];
    public string $triggerType = 'navbar'; // Options: 'navlist', 'navbar', 'navmenu'
    public array $localeNames = [];
    public array $localeFlags = [];

    public function mount(string $triggerType = 'navbar'): void
    {
        $this->locale           = app()->getLocale();
        $this->availableLocales = config('locales.available', []);
        $this->triggerType      = $triggerType;
        $this->localeNames      = config('locales.native_names', []);
        $this->localeFlags      = config('locales.flags', []);
    }

    public function setLocale(SwitchLocale $action, string $newLocale): void
    {
        $result = $action->handle(
            locale: $newLocale,
            referrer: request()->header('Referer')
        );

        if ( ! $result['success']) {
            return;
        }

        $this->locale = $result['locale'];

        Flux::toast(
            text: __('Language has been changed successfully.'),
            heading: __('Language Changed'),
            variant: 'success'
        );

        $this->redirect($result['referrer'] ?? route('home'), navigate: true);
    }
}

?>

<div>
    <flux:dropdown>
        {{-- Trigger dropdown --}}
        @switch($triggerType)
            @case('navbar')
                <flux:navbar.item
                    icon="language"
                    icon-trailing="chevron-down"
                    class="max-lg:hidden"/>
                @break

            @case('navlist')
                <flux:navlist.item icon="language" icon-trailing="chevron-down">
                    {{ $localeNames[$locale] }}
                </flux:navlist.item>
                @break

            @case('navmenu')
                <flux:navmenu.item icon="language">
                    {{ $localeNames[$locale] }}
                </flux:navmenu.item>
                @break
        @endswitch

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
                                         class="w-4 h-4 rounded-full border border-zinc-200 dark:border-white/10">
                                @endif
                                <span>{{ $localeNames[$availableLocale] }}</span>
                            </span>
                        </div>
                    </flux:menu.radio>
                @endforeach
            </flux:menu.radio.group>
        </flux:menu>
    </flux:dropdown>
</div>
