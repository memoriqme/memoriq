<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => [
        'http://memoriq.local',
        'https://memoriq.me',
    ],
    'allowed_origins_patterns' => [
        '/^chrome-extension:\/\/[a-zA-Z0-9]+$/',
    ],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];
