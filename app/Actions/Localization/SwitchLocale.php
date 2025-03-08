<?php

namespace App\Actions\Localization;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SwitchLocale
{
    public function handle(string $locale, ?string $referrer = null): array
    {
        $availableLocales = config('locales.available');

        if (! in_array($locale, $availableLocales)) {
            return [
                'success' => false,
                'locale' => App::getLocale(),
            ];
        }

        Session::put('locale', $locale);

        App::setLocale($locale);

        return [
            'success' => true,
            'locale' => $locale,
            'referrer' => $referrer,
        ];
    }
}
