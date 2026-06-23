<?php

namespace App\Data;

use App\Services\Tracking\Contracts\TracksVehicleState;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Carbon;
use JsonSerializable;

final readonly class RealtimeDeviceState implements Arrayable, JsonSerializable, TracksVehicleState
{
    public function __construct(
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
        public ?array $payload,
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
            latitude: $state['latitude'] ?? null,
            longitude: $state['longitude'] ?? null,
            geoAddress: $address,
            speed: isset($state['speed']) ? Speed::fromArray($state['speed']) : null,
            gpsStatus: $state['gps_status'] ?? null,
            angle: $state['angle'] ?? null,
            altitude: $state['altitude'] ?? null,
            acc: $state['acc'] ?? null,
            oil: $state['oil'] ?? null,
            voltage: $state['voltage'] ?? null,
            mileage: isset($state['mileage']) ? Distance::fromArray($state['mileage']) : null,
            temperature: $state['temperature'] ?? null,
            timestamps: TrackingTimestamps::fromArray($state['timestamps'] ?? []),
            payload: $state['payload'] ?? null,
        );
    }

    /**
     * @param array<string,mixed> $payload
     */
    public static function fromICruisePayload(array $payload): self
    {
        return new self(
            latitude: $payload['Latitude'] ?? null,
            longitude: $payload['Longitude'] ?? null,
            geoAddress: $payload['geo_address'] ?? null,
            speed: isset($payload['Velocity']) ? Speed::fromProvider((float) $payload['Velocity']) : null,
            gpsStatus: $payload['GpsStatus'] ?? null,
            angle: $payload['Angle'] ?? null,
            altitude: isset($payload['Altitude']) ? (float) $payload['Altitude'] : null,
            acc: $payload['Acc'] ?? null,
            oil: isset($payload['Oil']) ? (float) $payload['Oil'] : null,
            voltage: isset($payload['Voltage']) ? (float) $payload['Voltage'] : null,
            mileage: isset($payload['Mileage']) ? Distance::fromProvider((float) $payload['Mileage']) : null,
            temperature: isset($payload['Temperature']) ? (string) $payload['Temperature'] : null,
            timestamps: new TrackingTimestamps(
                gps: isset($payload['DateTime']) ? Carbon::parse($payload['DateTime']) : null,
                received: isset($payload['received_at']) ? Carbon::parse($payload['received_at']) : now(),
                lastSynced: null,
            ),
            payload: $payload,
        );
    }

    public function toArray(): array
    {
        return [
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
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
            'payload' => $this->payload,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function latitude(): ?float
    {
        return $this->latitude;
    }

    public function longitude(): ?float
    {
        return $this->longitude;
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
