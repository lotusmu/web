<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Available Languages and Default Language Configuration
    |--------------------------------------------------------------------------
    |
    | Define the languages supported by your application in this file.
    | You can specify which language should be used as the default.
    | This setting affects the localization behavior of your app.
    |
    */

    'available' => array_filter([
        'en',
        'ru',
        // Only include Bulgarian in local environment
        ...(env('APP_ENV') === 'local' ? ['bg'] : []),
    ]),

    'default' => env('APP_LOCALE', 'en'),

    /*
    |--------------------------------------------------------------------------
    | Language Names in Native Languages
    |--------------------------------------------------------------------------
    |
    | Define how each language name should be displayed in its native form
    | This allows showing language names in their own language
    |
    */

    'native_names' => [
        'en' => 'English',
        'bg' => 'Български',
        'ru' => 'Русский',
    ],

    /*
    |--------------------------------------------------------------------------
    | Language Flag Images
    |--------------------------------------------------------------------------
    |
    | Paths to flag images for each supported language.
    | Make sure all paths are relative to the public directory.
    |
    */

    'flags' => [
        'en' => '/images/flags/1x1/gb.svg',
        'bg' => '/images/flags/1x1/bg.svg',
        'ru' => '/images/flags/1x1/ru.svg',
    ],

    /*
    |--------------------------------------------------------------------------
    | Fallback Settings
    |--------------------------------------------------------------------------
    |
    | Configure fallback behavior for locales and assets.
    |
    */

    'fallback' => [
        'locale' => 'en',
        'flag' => '/images/flags/1x1/placeholder.svg',
    ],
];
