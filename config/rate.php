<?php

return [
    'default' => env('RATE_DRIVER', 'navasan'),

    'drivers' => [
        'navasan' => [
            'api_key' => env('NAVASAN_API_KEY', ''),
            'base_url' => env('NAVASAN_BASE_URL', ''),
            'service' => App\Services\Rate\NavasanService::class,
            'cache_time' => env('NAVASAN_CASH_TIME', 3)
        ],
        'irarz' => [
            'api_key' => env('IRARZ_API_KEY', '')
        ],
    ],
];
