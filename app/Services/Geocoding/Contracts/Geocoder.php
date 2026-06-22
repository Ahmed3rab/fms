<?php

namespace App\Services\Geocoding\Contracts;

use App\Data\Address;

interface Geocoder
{
    public function reverse(float $latitude, float $longitude, ?string $language = null): ?Address;
}
