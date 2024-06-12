<?php

return [
    'cli' => [
        'namespace' => env('APP_CLI_NAMESPACE', 'laravel'),
        'enable-cron' => env('RUNNING_CRON', false),
    ],
    'email' => [
        'noreply' => env('NO_REPLY_EMAIL'),
        'name' => env('NO_REPLY_NAME'),
    ],
    'default_sites' => [
        'MYR'
    ],
    'default_usd_rates' => [
        'MYR' => 4.612323
    ],
    'domains' => [
    ],
    'currency_countries' => [
        'MYR' => 'MY',
        'SGD' => 'SG',
        'THB' => "TH",
        'VND' => 'VN',
        'IDR' => 'ID',
        'USD' => 'US',
        'MMK' => 'MM',
        'AUD' => 'AU',
        'HKD' => 'HK',
        'BRL' => 'BR',
        'TWD' => 'TW',
        'BND' => "BN",
        'INR' => 'IN',
    ]
];