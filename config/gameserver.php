<?php

return [
    'port' => env('GAME_SERVER_PORT', 44405),
    'socket_timeout' => env('GAME_SERVER_SOCKET_TIMEOUT', 0.5),
    'cache' => [
        'status_ttl' => env('GAME_SERVER_STATUS_CACHE_TTL', 3),
        'count_ttl' => env('GAME_SERVER_COUNT_CACHE_TTL', 3),
    ],
];
