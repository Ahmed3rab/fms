<?php

namespace App\Services\Geocoding;

use App\Data\GeoLocationAddress;
use App\Services\Geocoding\Contracts\Geocoder;
use Illuminate\Support\Facades\Cache;

class CachedGeocoder implements Geocoder
{
    public function __construct(protected Geocoder $geocoder) {}

    public function reverse(float $latitude, float $longitude, ?string $language = 'ar'): ?GeoLocationAddress
    {
        $key = sprintf(
            'geocode:%s:%s:%s',
            round($latitude, 5),
            round($longitude, 5),
            $language,
        );
        $cached = Cache::store('redis')->get($key);

        if ($cached) {
            return GeoLocationAddress::fromArray($cached);
        }

        $address = $this->geocoder->reverse(
            $latitude,
            $longitude,
            $language,
        );

        if ($address) {
            Cache::store('redis')->put(
                $key,
                $address->toArray(),
                now()->addDays(30),
            );
        }

        return $address;
    }
}
