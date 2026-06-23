<?php

namespace App\Data;

final readonly class ResolvedDeviceState
{
    public function __construct(
        public string $source,
        public VehicleStatus $status,
        public ?float $latitude,
        public ?float $longitude,
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
}
