<?php

namespace App\Actions\Localization;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SwitchLocale
{
    public function handle(string $locale, ?string $referrer = null): array
    {
        if (empty($locale) || ! in_array($locale, config('locales.available', ['en']), true)) {
            return [
                'success' => false,
                'locale' => App::getLocale(),
                'message' => 'Invalid locale',
                'fallback_used' => true,
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
