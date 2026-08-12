<?php

return [

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    // Comma-separated in FRONTEND_URL if you ever need more than one
    // origin (e.g. staging + production) — split it here.
    'allowed_origins' => array_filter(explode(',', env('FRONTEND_URL', 'http://localhost:3000'))),

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,

];
