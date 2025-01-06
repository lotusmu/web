<?php

/*
|--------------------------------------------------------------------------
| Social Media Links and Platform Configuration
|--------------------------------------------------------------------------
|
| Define your social media platform URLs in this configuration file.
| These links can be used throughout your application consistently.
| This keeps all social media references in one central place.
|
*/

return [
    'links' => [
        'discord' => env('SOCIAL_DISCORD_URL'),
        'facebook' => env('SOCIAL_FACEBOOK_URL'),
        'youtube' => env('SOCIAL_YOUTUBE_URL'),
    ],
];
