<?php

namespace App\Data;

use App\Models\DeviceState;
use App\Services\Tracking\VehicleStatus\ConnectivityStatusResolver;
use App\Services\Tracking\VehicleStatus\MovementStatusResolver;

final readonly class ResolvedDeviceState
{
    public function __construct(
        public string $source,
        public VehicleStatus $status,
        public ?float $latitude,
        public ?float $longitude,
        public ?GeoLocationAddress $geoAddress,
        public ?float $speed,
        public mixed $gpsTime,
        public ?bool $gpsStatus,
        public ?int $angle,
        public ?float $altitude,
        public ?string $acc,
        public ?float $oil,
        public ?float $voltage,
        public ?float $mileage,
        public ?string $temperature,
        public mixed $receivedAt,
        public mixed $lastSyncedAt,
    ) {}

    public static function fromDatabase(DeviceState $state): self
    {
        return self::fromState(
            source: 'database',
            state: $state,
        );
    }

    /**
     * @param array<string,mixed> $state
     */
    public static function fromRealtime(array $state): self
    {
        return self::fromState(
            source: 'realtime',
            state: $state,
        );
    }

    /**
     * @param array<string,mixed>|object $state
     */
    private static function fromState(string $source, array|object $state): self
    {
        $connectionResolver = app(ConnectivityStatusResolver::class);
        $movementResolver = app(MovementStatusResolver::class);

        return new self(
            source: $source,
            status: new VehicleStatus(
                connection: $connectionResolver->resolve($state),
                movement: $movementResolver->resolve($state),
            ),
            latitude: data_get($state, 'latitude'),
            longitude: data_get($state, 'longitude'),
            geoAddress: self::resolveGeoAddress($state),
            speed: data_get($state, 'speed'),
            gpsTime: data_get($state, 'gps_time'),
            gpsStatus: data_get($state, 'gps_status'),
            angle: data_get($state, 'angle'),
            altitude: data_get($state, 'altitude'),
            acc: data_get($state, 'acc'),
            oil: data_get($state, 'oil'),
            voltage: data_get($state, 'voltage'),
            mileage: data_get($state, 'mileage'),
            temperature: data_get($state, 'temperature'),
            receivedAt: data_get($state, 'received_at'),
            lastSyncedAt: data_get($state, 'last_synced_at'),
        );
    }

    /**
     * @param mixed[]|object $state
     */
    private static function resolveGeoAddress(array|object $state): ?GeoLocationAddress
    {
        $address = data_get($state, 'geo_address');

        if ($address instanceof GeoLocationAddress) {
            return $address;
        }

        if (is_array($address)) {
            return GeoLocationAddress::fromArray($address);
        }

        return null;
    }
}
