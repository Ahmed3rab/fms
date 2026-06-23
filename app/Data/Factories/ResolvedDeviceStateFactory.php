<?php

namespace App\Data\Factories;

use App\Data\ResolvedDeviceState;
use App\Data\VehicleStatus;
use App\Enums\DeviceStateSource;
use App\Services\Tracking\Contracts\TracksVehicleState;
use App\Services\Tracking\VehicleStatus\MovementStatusResolver;
use App\Services\Tracking\VehicleStatus\ConnectivityStatusResolver;

class ResolvedDeviceStateFactory
{
    public function __construct(protected ConnectivityStatusResolver $connectivity, protected MovementStatusResolver $movement) {}

    public function make(TracksVehicleState $state, DeviceStateSource $source): ResolvedDeviceState
    {
        return new ResolvedDeviceState(
            source: $source->value,
            status: $this->resolveStatus($state),
            coordinates: $state->coordinates(),
            geoAddress: $state->geoAddress(),
            speed: $state->speed(),
            gpsStatus: $state->gpsStatus(),
            angle: $state->angle(),
            altitude: $state->altitude(),
            acc: $state->acc(),
            oil: $state->oil(),
            voltage: $state->voltage(),
            mileage: $state->mileage(),
            temperature: $state->temperature(),
            timestamps: $state->timestamps(),
        );
    }

    private function resolveStatus(TracksVehicleState $state): VehicleStatus
    {
        return new VehicleStatus(
            connection: $this->connectivity->resolve($state),
            movement: $this->movement->resolve($state),
        );
    }

}
