<?php

namespace App\Services\Tracking\VehicleStatus;

use App\Enums\MovementStatus;
use App\Services\Tracking\Contracts\TracksVehicleState;

class MovementStatusResolver
{
    private const MOVING_SPEED_THRESHOLD = 2;

    /**
     * @param mixed[]|object|null $state
     */
    public function resolve(?TracksVehicleState $state): MovementStatus
    {
        $speed = $state->speed();

        if ($speed === null) {
            return MovementStatus::NoGps;
        }

        if (! $state->gpsStatus()) {
            return MovementStatus::NoGps;
        }

        if ($speed->isMoving()) {
            return MovementStatus::Moving;
        }

        return strtoupper($state->acc() ?? '') === 'ON'
            ? MovementStatus::Idling
            : MovementStatus::Parked;
    }
}
