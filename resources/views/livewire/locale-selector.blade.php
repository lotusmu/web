<?php

use App\Actions\Localization\SwitchLocale;
use Illuminate\Contracts\View\View;
use Livewire\Volt\Component;

new class extends Component {
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

    public function setLocale(string $newLocale, ?SwitchLocale $action = null): void
    {
        $action = $action ?? app(SwitchLocale::class);

        // Skip if locale hasn't changed
        if ($this->locale === $newLocale) {
            return;
        }

        // Validate locale against available options
        if ( ! in_array($newLocale, $this->availableLocales, true)) {
            Flux::toast(
                text: __('Invalid language selected.'),
                heading: __('Error'),
                variant: 'danger'
            );

            return;
        }

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
                    {{ $localeNames[$locale] ?? $locale }}
                </flux:navlist.item>
                @break

            @case('navmenu')
                <flux:navmenu.item icon="language">
                    {{ $localeNames[$locale] ?? $locale }}
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
                                <img
                                    src="{{ asset($localeFlags[$availableLocale] ?? config('locales.fallback.flag')) }}"
                                    alt="{{ $localeNames[$availableLocale] ?? $availableLocale }} flag"
                                    class="w-4 h-4 rounded-full border border-zinc-200 dark:border-white/10">
                                <span>{{ $localeNames[$availableLocale] ?? $availableLocale }}</span>
                            </span>
                        </div>
                    </flux:menu.radio>
                @endforeach
            </flux:menu.radio.group>
        </flux:menu>
    </flux:dropdown>
</div>
