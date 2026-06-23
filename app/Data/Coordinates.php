<?php

namespace App\Data;

use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

final readonly class Coordinates implements Arrayable, JsonSerializable
{
    public function __construct(public float $latitude, public float $longitude) {}

    public static function fromProvider(float $latitude, float $longitude): self
    {
        return new self($latitude, $longitude);
    }

    /**
     * @param array{latitude:float|int,longitude:float|int} $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            latitude: (float) $data['latitude'],
            longitude: (float) $data['longitude'],
        );
    }

    public function isValid(): bool
    {
        return $this->latitude >= -90
            && $this->latitude <= 90
            && $this->longitude >= -180
            && $this->longitude <= 180;
    }

    public function toArray(): array
    {
        return [
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
