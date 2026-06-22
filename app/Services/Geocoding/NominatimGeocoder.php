<?php

namespace App\Services\Geocoding;

use App\Services\Geocoding\Contracts\Geocoder;
use Illuminate\Support\Facades\Http;

class NominatimGeocoder implements Geocoder
{
    /**
     * @return array<string,mixed>|null
     */
    public function reverse(float $latitude, float $longitude, ?string $language = 'ar'): ?array
    {
        $response = Http::acceptJson()
            ->withHeaders([
                // Nominatim requires a valid User-Agent.
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
        return [
            'display_name' => $data['display_name'] ?? null,

            'latitude'  => $data['lat'] ?? null,
            'longitude' => $data['lon'] ?? null,

            'city' => data_get($data, 'address.city'),
            'state' => data_get($data, 'address.state'),
            'country' => data_get($data, 'address.country'),
            'country_code' => data_get($data, 'address.country_code'),

            'provider' => 'nominatim',
            'payload' => $data,
        ];
    }
}
