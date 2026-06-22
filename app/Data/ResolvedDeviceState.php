<?php

namespace App\Data;

use App\Models\DeviceState;
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

    public static function fromDatabase(DeviceState $state, VehicleStatus $status): self
    {
        return new self(
            source: 'database',
            status: $status,
            latitude: $state->latitude,
            longitude: $state->longitude,
            geoAddress: $state->geo_address,
            speed: $state->speed,
            gpsTime: $state->gps_time,
            gpsStatus: $state->gps_status,
            angle: $state->angle,
            altitude: $state->altitude,
            acc: $state->acc,
            oil: $state->oil,
            voltage: $state->voltage,
            mileage: $state->mileage,
            temperature: $state->temperature,
            receivedAt: null,
            lastSyncedAt: $state->last_synced_at,
        );
    }

    public static function fromRealtime(RealtimeDeviceState $state, VehicleStatus $status): self
    {
        return new self(
            source: 'realtime',
            status: $status,
            latitude: $state->latitude,
            longitude: $state->longitude,
            geoAddress: $state->geoAddress,
            speed: $state->speed,
            gpsTime: $state->gpsTime,
            gpsStatus: $state->gpsStatus,
            angle: $state->angle,
            altitude: $state->altitude,
            acc: $state->acc,
            oil: $state->oil,
            voltage: $state->voltage,
            mileage: $state->mileage,
            temperature: $state->temperature,
            receivedAt: $state->receivedAt,
            lastSyncedAt: null,
        );
    }

}
