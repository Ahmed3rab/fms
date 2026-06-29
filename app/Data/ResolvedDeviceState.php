<?php

namespace App\Data;

use App\Services\Tracking\Contracts\TracksVehicleState;
use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

/**
 * @implements Arrayable<array-key,mixed>
 */
final readonly class ResolvedDeviceState implements Arrayable, JsonSerializable, TracksVehicleState
{
    public function __construct(
        public ?string $deviceUuid,
        public string $source,
        public VehicleStatus $status,
        public ?Coordinates $coordinates,
        public ?GeoLocationAddress $geoAddress,
        public ?Speed $speed,
        public ?bool $gpsStatus,
        public ?int $angle,
        public ?float $altitude,
        public ?Ignition $ignitionState,
        public ?float $oil,
        public ?float $voltage,
        public ?Distance $mileage,
        public ?string $temperature,
        public TrackingTimestamps $timestamps,
    ) {}


    /**
     * @param array<string,mixed> $state
     */
    public static function fromArray(array $state): self
    {
        $address = $state['geo_address'] ?? null;

        if (is_array($address)) {
            $address = GeoLocationAddress::fromArray($address);
        }

        return new self(
            deviceUuid: $state['device_uuid'] ?? null,
            source: $state['source'],
            status: VehicleStatus::fromArray($state['status']),
            coordinates: isset($state['coordinates']) ? Coordinates::fromArray($state['coordinates']) : null,
            geoAddress: $address,
            speed: isset($state['speed']) ? Speed::fromArray($state['speed']) : null,
            gpsStatus: $state['gps_status'] ?? null,
            angle: $state['angle'] ?? null,
            altitude: $state['altitude'] ?? null,
            ignitionState: isset($state['ignition']) ? Ignition::fromArray($state['ignition']) : null,
            oil: $state['oil'] ?? null,
            voltage: $state['voltage'] ?? null,
            mileage: isset($state['mileage']) ? Distance::fromArray($state['mileage']) : null,
            temperature: $state['temperature'] ?? null,
            timestamps: TrackingTimestamps::fromArray($state['timestamps'] ?? []),
        );
    }


    public function toArray(): array
    {
        return [
            'source' => $this->source,
            'status' => $this->status,
            'coordinates' => $this->coordinates,
            'geo_address' => $this->geoAddress,
            'speed' => $this->speed,
            'gps_status' => $this->gpsStatus,
            'angle' => $this->angle,
            'altitude' => $this->altitude,
            'ignition' => $this->ignition(),
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

    public function deviceUuid(): ?string
    {
        return $this->deviceUuid;
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

    public function ignition(): ?Ignition
    {
        return $this->ignitionState;
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
