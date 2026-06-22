<?php

namespace App\Services\Geocoding\Contracts;

interface Geocoder
{
    /**
     * Reverse geocode coordinates.
     *
     * @return array<string,mixed>|null
     */
    public function reverse(float $latitude, float $longitude, ?string $language = null): ?array;
}
