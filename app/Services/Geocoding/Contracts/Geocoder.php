<?php

namespace App\Services\Geocoding\Contracts;

use App\Data\Coordinates;
use App\Data\GeoLocationAddress;

interface Geocoder
{
    public function reverse(Coordinates $coordinates, ?string $language = null): ?GeoLocationAddress;
}
