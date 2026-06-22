<?php

namespace App\Services\Geocoding;

use App\Data\Address;
use App\Services\Geocoding\Contracts\Geocoder;
use Illuminate\Support\Facades\Http;

class NominatimGeocoder implements Geocoder
{
    public function reverse(float $latitude, float $longitude, ?string $language = 'ar'): ?Address
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
        return new Address(
            displayName: $data['display_name'] ?? null,
            city: $data['address']['city'] ?? $data['address']['town'] ?? $data['address']['village'] ?? null,
            state: $data['address']['state'] ?? null,
            country: $data['address']['country'] ?? null,
            countryCode: $data['address']['country_code'] ?? null,
            placeId: $data['place_id'] ?? null,
            osmType: $data['osm_type'] ?? null,
            osmId: $data['osm_id'] ?? null,
        );
    }
}
