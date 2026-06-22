<?php

namespace App\Services\Geocoding;

use App\Services\Geocoding\Contracts\Geocoder;
use Illuminate\Support\Facades\Cache;

class CachedGeocoder implements Geocoder
{
    public function __construct(protected Geocoder $geocoder) {}

    public function reverse(float $latitude, float $longitude, ?string $language = 'ar'): ?array
    {
        $key = sprintf(
            'geocode:%s:%s:%s',
            round($latitude, 5),
            round($longitude, 5),
            $language,
        );

        return Cache::remember(
            $key,
            now()->addDays(30),
            fn() => $this->geocoder->reverse(
                $latitude,
                $longitude,
                $language,
            ),
        );
    }
}
