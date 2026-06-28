<?php

use App\Services\ICruise\ICruiseTrackingProvider;

return [

    /*
    |--------------------------------------------------------------------------
    | Active Tracking Provider
    |--------------------------------------------------------------------------
    */

    'provider' => env(
        'TRACKING_PROVIDER',
        ICruiseTrackingProvider::class,
    ),
    'gateway' => [

        'host' => env('TRACKING_GATEWAY_HOST', '0.0.0.0'),
        'port' => env('TRACKING_GATEWAY_PORT', 9502),
        'worker_num' => env('TRACKING_GATEWAY_WORKERS', 1),
        'max_connections' => env('TRACKING_GATEWAY_MAX_CONNECTIONS', 10000),
        'heartbeat_idle_time' => env('TRACKING_GATEWAY_HEARTBEAT_IDLE_TIME', 120),
        'heartbeat_check_interval' => env('TRACKING_GATEWAY_HEARTBEAT_CHECK_INTERVAL', 5_000),

    ],
];
