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

];
