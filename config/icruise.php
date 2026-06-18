<?php

return [
    'url' => env(
        'ICRUISE_URL',
        'http://41.208.84.29:8000/WebProcessorApi.ashx'
    ),
    'origin' => env(
        'ICRUISE_ORIGIN',
        'http://41.208.84.29'
    ),
    'language_type' => env(
        'ICRUISE_LANGUAGE_TYPE',
        '2B72ABC6-19D7-4653-AAEE-0BE542026D46'
    ),
    'username' => env('ICRUISE_USERNAME'),

    'password' => env('ICRUISE_PASSWORD'),
];
