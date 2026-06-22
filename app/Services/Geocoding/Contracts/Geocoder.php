<?php

namespace App\Services\Geocoding\Contracts;

use App\Data\GeoLocationAddress;

interface Geocoder
{
    public function reverse(float $latitude, float $longitude, ?string $language = null): ?GeoLocationAddress;
}
