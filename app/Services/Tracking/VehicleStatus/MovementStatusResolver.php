<?php

namespace App\Services\Tracking\VehicleStatus;

use App\Enums\IgnitionStatus;
use App\Enums\MovementStatus;
use App\Services\Tracking\Contracts\TracksVehicleState;

class MovementStatusResolver
{
    public function resolve(?TracksVehicleState $state): MovementStatus
    {
        if ($state === null) {
            return MovementStatus::NoGps;
        }

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

        return $state->ignition()?->status === IgnitionStatus::On
            ? MovementStatus::Idling
            : MovementStatus::Parked;
    }
}
