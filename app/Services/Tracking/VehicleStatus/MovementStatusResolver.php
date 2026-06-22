<?php

namespace App\Services\Tracking\VehicleStatus;

use App\Enums\MovementStatus;

class MovementStatusResolver
{
    private const MOVING_SPEED_THRESHOLD = 2;
    /**
     * @param mixed[]|object|null $state
     */
    public function resolve(array|object|null $state): MovementStatus
    {
        if ($state === null) {
            return MovementStatus::NoGps;
        }

        $gps = (bool) data_get($state, 'gps_status');

        if (! $gps) {
            return MovementStatus::NoGps;
        }

        $speed = (float) data_get($state, 'speed');

        if ($speed > self::MOVING_SPEED_THRESHOLD) {
            return MovementStatus::Moving;
        }

        $acc = strtoupper((string) data_get($state, 'acc'));

        return $acc === 'ON'
            ? MovementStatus::Idling
            : MovementStatus::Parked;
    }

}
