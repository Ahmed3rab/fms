<?php

namespace App\Services\ICruise\Mappers;

use App\Data\Coordinates;
use App\Data\Distance;
use App\Data\History;
use App\Data\HistoryPoint;
use App\Data\Ignition;
use App\Data\Speed;
use App\Data\TrackingTimestamps;
use App\Models\Device;
use App\Services\Geocoding\Contracts\Geocoder;
use Illuminate\Support\Carbon;

class HistoryMapper
{
    protected array $geocodeCache = [];

    public function __construct(protected Geocoder $geoCoder) {}

    /**
     * @param array<int,array<string,mixed>> $points
     */
    public function map(Device $device, array $points): History
    {
        return new History(
            collect($points)->map(fn(array $point) => $this->mapPoint($device, $point))
        );
    }

    /**
     * @param array<string,mixed> $point
     */
    protected function mapPoint(Device $device, array $point): HistoryPoint
    {
        $coordinates = isset($point['Latitude'], $point['Longitude'])
                ? Coordinates::fromProvider(
                    (float) $point['Latitude'],
                    (float) $point['Longitude'],
                ) : null;

        $geoAddress = null;

        if ($coordinates) {
            $key = "{$coordinates->latitude},{$coordinates->longitude}";

            if (! isset($this->geocodeCache[$key])) {
                $this->geocodeCache[$key] = $this->geoCoder->reverse($coordinates);
            }

            $geoAddress = $this->geocodeCache[$key];
        }

        return new HistoryPoint(
            deviceUuid: $device->uuid,
            coordinates: $coordinates,
            geoAddress: $this->geocodeCache[$key],
            speed: isset($point['Velocity']) ? Speed::fromProvider((float) $point['Velocity']) : null,
            gpsStatus: $point['GpsStatus'] ?? null,
            angle: $point['Angle'] ?? null,
            altitude: isset($point['Altitude']) ? (float) $point['Altitude'] : null,
            ignition: Ignition::fromProvider($point['Acc'] ?? null),
            oil: isset($point['Oil']) ? (float) $point['Oil'] : null,
            voltage: isset($point['Voltage']) ? (float) $point['Voltage'] : null,
            mileage: isset($point['Mileage']) ? Distance::fromProvider((float) $point['Mileage']) : null,
            temperature: isset($point['Temperature']) ? (string) $point['Temperature'] : null,
            timestamps: new TrackingTimestamps(
                gps: isset($point['DateTime']) ? Carbon::parse($point['DateTime']) : null,
                received: null,
                lastSynced: null,
            ),
        );
    }
}
