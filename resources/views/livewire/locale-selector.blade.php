<?php

use App\Actions\Localization\SwitchLocale;
use Livewire\Volt\Component;

new class extends Component {
    public string $locale;
    public array $availableLocales;

    public function mount(): void
    {
        $this->locale           = app()->getLocale();
        $this->availableLocales = config('locales.available');
    }

    public function setLocale(SwitchLocale $action, string $newLocale): void
    {
        $result = $action->handle(
            $newLocale,
            request()->header('Referer')
        );

        if ($result['success']) {
            $this->locale = $result['locale'];

            $this->redirect(($result['referrer'] ?? route('home')), navigate: true);

            Flux::toast(
                text: __('Language has been changed successfully.'),
                heading: __('Language Changed'),
                variant: 'success'
            );
        }
    }
}

?>

<div>
    <flux:dropdown>
        <flux:button variant="subtle" size="sm" icon="globe-alt" :tooltip="__('Change language')" class="max-lg:hidden"/>
        <flux:menu>
            @foreach($availableLocales as $availableLocale)
                <flux:menu.item
                    wire:key="{{ $availableLocale }}"
                    wire:click="setLocale('{{ $availableLocale }}')"
                    :active="$locale === $availableLocale"
                >
                    {{ strtoupper($availableLocale) }}
                </flux:menu.item>
            @endforeach
        </flux:menu>
    </flux:dropdown>
</div>
