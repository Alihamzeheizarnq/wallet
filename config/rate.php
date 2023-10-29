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
        'example' => [
            'api_key' => env('EXAMPLE_API_KEY', ''),
            'base_url' => env('EXAMPLE_BASE_URL', ''),
            'service' => App\Services\Rate\ExampleRateService::class,
            'cache_time' => env('EXAMPLE_CASH_TIME', 3)
        ],
    ],
];
