<?php

namespace App\Services\ICruise\Mappers;

use App\Data\Coordinates;
use App\Data\Distance;
use App\Data\RealtimeDeviceState;
use App\Data\Speed;
use App\Data\TrackingTimestamps;
use App\Services\Geocoding\Contracts\Geocoder;
use App\Services\Tracking\Resolvers\Contract\TrackingDeviceResolver;
use Illuminate\Support\Carbon;

class RealtimeStateMapper
{
    public function __construct(protected TrackingDeviceResolver $trackingDeviceResolver, protected Geocoder $geoCoder) {}

    /**
     * @param array<string,mixed> $payload
     */
    public function map(array $payload): RealtimeDeviceState
    {
        $coordinates = isset($payload['Latitude'], $payload['Longitude'])
                ? Coordinates::fromProvider(
                    (float) $payload['Latitude'],
                    (float) $payload['Longitude'],
                ) : null;

        $geoAddress = $this->geoCoder->reverse($coordinates);
        return new RealtimeDeviceState(
            deviceUuid: $this->trackingDeviceResolver->uuidFromProvider($payload['SystemNo']),
            coordinates: $coordinates,
            geoAddress: $geoAddress ?? null,
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
                received: now(),
                lastSynced: null,
            ),
            payload: $payload,
        );
    }
}
