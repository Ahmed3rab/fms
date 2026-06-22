<?php

namespace App\Services\Geocoding;

use App\Data\GeoLocationAddress;
use App\Services\Geocoding\Contracts\Geocoder;
use Illuminate\Support\Facades\Http;

class NominatimGeocoder implements Geocoder
{
    public function reverse(float $latitude, float $longitude, ?string $language = 'ar'): ?GeoLocationAddress
    {
        $response = Http::acceptJson()
            ->withHeaders([
                'User-Agent' => config('app.name') . '/1.0',
            ])
            ->get(
                'https://nominatim.openstreetmap.org/reverse',
                [
                    'format' => 'json',
                    'lat' => $latitude,
                    'lon' => $longitude,
                    'zoom' => 18,
                    'addressdetails' => 1,
                    'accept-language' => $language,
                ]
            );

        if (! $response->successful()) {
            return null;
        }
        $data = $response->json();
        return GeoLocationAddress::fromArray([
            'display_name' => $data['display_name'] ?? null,
            'city' => $data['address']['city'] ?? $data['address']['town'] ?? $data['address']['village'] ?? null,
            'state' => $data['address']['state'] ?? null,
            'country' => $data['address']['country'] ?? null,
            'country_code' => $data['address']['country_code'] ?? null,
            'place_id' => $data['place_id'] ?? null,
            'osm_type' => $data['osm_type'] ?? null,
            'osm_id' => $data['osm_id'] ?? null,
        ]);
    }
}
