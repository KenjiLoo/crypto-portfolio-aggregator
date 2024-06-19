<?php

return [
    'cli' => [
        'namespace' => env('APP_CLI_NAMESPACE', 'laravel'),
        'enable-cron' => env('RUNNING_CRON', false),
    ],
    'email' => [
        'noreply' => env('NO_REPLY_EMAIL'),
        'name' => env('NO_REPLY_NAME'),
    ]
];
