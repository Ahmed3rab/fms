<?php

namespace App\Data;

use JsonSerializable;

final readonly class GeoLocationAddress implements JsonSerializable
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

    /**
     * @param array<int,mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            displayName: $data['display_name'] ?? null,
            city: $data['city'] ?? null,
            state: $data['state'] ?? null,
            country: $data['country'] ?? null,
            countryCode: $data['country_code'] ?? null,
            placeId: $data['place_id'] ?? null,
            osmType: $data['osm_type'] ?? null,
            osmId: $data['osm_id'] ?? null,
        );
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
