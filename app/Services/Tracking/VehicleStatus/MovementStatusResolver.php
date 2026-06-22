<?php

namespace App\Services\Tracking\VehicleStatus;

use App\Enums\VehicleStatus;

class MovementStatusResolver
{
    private const MOVING_SPEED_THRESHOLD = 2;
    /**
     * @param mixed[]|object|null $state
     */
    public function resolve(array|object|null $state): VehicleStatus
    {
        if ($state === null) {
            return VehicleStatus::NoGps;
        }

        $gps = (bool) data_get($state, 'gps_status');

        if (! $gps) {
            return VehicleStatus::NoGps;
        }

        $speed = (float) data_get($state, 'speed');

        if ($speed > self::MOVING_SPEED_THRESHOLD) {
            return VehicleStatus::Moving;
        }

        $acc = strtoupper((string) data_get($state, 'acc'));

        return $acc === 'ON'
            ? VehicleStatus::Idling
            : VehicleStatus::Parked;
    }

}
