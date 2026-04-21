<?php

return [
    'url' => env('FRONTEND_URL', 'http://localhost:3000'),
    'paths' => [
        'payment_success' => '/payment-success',
        'payment_failed' => '/payment-failed',
        'payment_cancelled' => '/payment-cancel',
        'payment_error' => '/payment-cancel',
    ]
];
