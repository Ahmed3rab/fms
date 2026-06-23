<?php

namespace App\Services\ICruise\Mappers;

use App\Data\Coordinates;
use App\Data\Distance;
use App\Data\History;
use App\Data\HistoryPoint;
use App\Data\Speed;
use App\Data\TrackingTimestamps;
use Illuminate\Support\Carbon;

class HistoryMapper
{
    /**
     * @param array<int,array<string,mixed>> $points
     */
    public function map(array $points): History
    {
        return new History(
            collect($points)->map(fn(array $point) => $this->mapPoint($point))
        );
    }

    /**
     * @param array<string,mixed> $point
     */
    protected function mapPoint(array $point): HistoryPoint
    {
        return new HistoryPoint(
            coordinates: isset($point['Latitude'], $point['Longitude'])
                ? Coordinates::fromProvider((float) $point['Latitude'], (float) $point['Longitude'])
                : null,
            geoAddress: null,
            speed: isset($point['Velocity']) ? Speed::fromProvider((float) $point['Velocity']) : null,
            gpsStatus: $point['GpsStatus'] ?? null,
            angle: $point['Angle'] ?? null,
            altitude: isset($point['Altitude']) ? (float) $point['Altitude'] : null,
            acc: $point['Acc'] ?? null,
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
