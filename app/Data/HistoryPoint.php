<?php

namespace App\Data;

use App\Services\Tracking\Contracts\TracksVehicleState;
use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

final readonly class HistoryPoint implements TracksVehicleState, Arrayable, JsonSerializable
{
    public function __construct(
        public ?Coordinates $coordinates,
        public ?GeoLocationAddress $geoAddress,
        public ?Speed $speed,
        public ?bool $gpsStatus,
        public ?int $angle,
        public ?float $altitude,
        public ?string $acc,
        public ?float $oil,
        public ?float $voltage,
        public ?Distance $mileage,
        public ?string $temperature,
        public TrackingTimestamps $timestamps,
    ) {}

    public function toArray(): array
    {
        return [
            'coordinates' => $this->coordinates,
            'geo_address' => $this->geoAddress,
            'speed' => $this->speed,
            'gps_status' => $this->gpsStatus,
            'angle' => $this->angle,
            'altitude' => $this->altitude,
            'acc' => $this->acc,
            'oil' => $this->oil,
            'voltage' => $this->voltage,
            'mileage' => $this->mileage,
            'temperature' => $this->temperature,
            'timestamps' => $this->timestamps,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function coordinates(): ?Coordinates
    {
        return $this->coordinates;
    }

    public function geoAddress(): ?GeoLocationAddress
    {
        return $this->geoAddress;
    }

    public function speed(): ?Speed
    {
        return $this->speed;
    }

    public function gpsStatus(): ?bool
    {
        return $this->gpsStatus;
    }

    public function angle(): ?int
    {
        return $this->angle;
    }

    public function altitude(): ?float
    {
        return $this->altitude;
    }

    public function acc(): ?string
    {
        return $this->acc;
    }

    public function oil(): ?float
    {
        return $this->oil;
    }

    public function voltage(): ?float
    {
        return $this->voltage;
    }

    public function mileage(): ?Distance
    {
        return $this->mileage;
    }

    public function temperature(): ?string
    {
        return $this->temperature;
    }

    public function timestamps(): TrackingTimestamps
    {
        return $this->timestamps;
    }
}
