<?php

namespace App\Data;

final readonly class Address
{
    public function __construct(
        public ?string $displayName,
        public ?string $city,
        public ?string $state,
        public ?string $country,
        public ?string $countryCode,
        public ?int $placeId,
        public ?string $osmType,
        public ?int $osmId,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'display_name' => $this->displayName,
            'city' => $this->city,
            'state' => $this->state,
            'country' => $this->country,
            'country_code' => $this->countryCode,
            'place_id' => $this->placeId,
            'osm_type' => $this->osmType,
            'osm_id' => $this->osmId,
        ];
    }
}
