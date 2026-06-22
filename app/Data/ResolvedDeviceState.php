<?php

namespace App\Data;

use Illuminate\Support\Carbon;

final readonly class ResolvedDeviceState
{
    public function __construct(
        public string $source,
        public VehicleStatus $status,
        public ?float $latitude,
        public ?float $longitude,
        public ?GeoLocationAddress $geoAddress,
        public ?float $speed,
        public ?Carbon $gpsTime,
        public ?bool $gpsStatus,
        public ?int $angle,
        public ?float $altitude,
        public ?string $acc,
        public ?float $oil,
        public ?float $voltage,
        public ?float $mileage,
        public ?string $temperature,
        public ?Carbon $receivedAt,
        public ?Carbon $lastSyncedAt,
    ) {}
}
