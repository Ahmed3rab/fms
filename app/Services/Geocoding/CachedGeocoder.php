<?php

namespace App\Services\Geocoding;

use App\Data\Coordinates;
use App\Data\GeoLocationAddress;
use App\Services\Geocoding\Contracts\Geocoder;
use Illuminate\Support\Facades\Cache;

class CachedGeocoder implements Geocoder
{
    public function __construct(protected Geocoder $geocoder) {}

    public function reverse(Coordinates $coordinates, ?string $language = 'ar'): ?GeoLocationAddress
    {
        $key = sprintf(
            'geocode:%s:%s:%s',
            round($coordinates->latitude, 5),
            round($coordinates->longitude, 5),
            $language,
        );
        $cached = Cache::store('redis')->get($key);

        if ($cached) {
            return GeoLocationAddress::fromArray($cached);
        }

        $address = $this->geocoder->reverse($coordinates, $language);

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
