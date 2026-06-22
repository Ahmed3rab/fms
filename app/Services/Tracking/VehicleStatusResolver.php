<?php

namespace App\Services\Tracking;

use App\Enums\VehicleStatus;

class VehicleStatusResolver
{
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

        if ($speed > 2) {
            return VehicleStatus::Moving;
        }

        $acc = strtoupper((string) data_get($state, 'acc'));

        return $acc === 'ON'
            ? VehicleStatus::Idling
            : VehicleStatus::Parked;
    }

}
