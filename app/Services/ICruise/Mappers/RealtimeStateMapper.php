<?php

namespace App\Services\ICruise\Mappers;

use App\Data\Coordinates;
use App\Data\Distance;
use App\Data\RealtimeDeviceState;
use App\Data\Speed;
use App\Data\TrackingTimestamps;
use Illuminate\Support\Carbon;

class RealtimeStateMapper
{
    /**
     * @param array<string,mixed> $payload
     */
    public function map(array $payload): RealtimeDeviceState
    {
        return new RealtimeDeviceState(
            coordinates: isset($payload['Latitude'], $payload['Longitude'])
                ? Coordinates::fromProvider(
                    (float) $payload['Latitude'],
                    (float) $payload['Longitude'],
                )
                : null,
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
}
