<?php

namespace App\Data\Factories;

use App\Data\RealtimeDeviceState;
use App\Data\ResolvedDeviceState;
use App\Data\VehicleStatus;
use App\Models\DeviceState;
use App\Services\Tracking\VehicleStatus\MovementStatusResolver;
use App\Services\Tracking\VehicleStatus\ConnectivityStatusResolver;

class ResolvedDeviceStateFactory
{
    public function __construct(protected ConnectivityStatusResolver $connectivity, protected MovementStatusResolver $movement) {}

    public function fromDatabase(DeviceState $state): ResolvedDeviceState
    {
        return ResolvedDeviceState::fromDatabase(
            $state,
            $this->resolveStatus($state),
        );
    }

    public function fromRealtime(RealtimeDeviceState $state): ResolvedDeviceState
    {
        return ResolvedDeviceState::fromRealtime(
            $state,
            $this->resolveStatus($state)
        );
    }
    private function resolveStatus(DeviceState|RealtimeDeviceState $state): VehicleStatus
    {
        return new VehicleStatus(
            connection: $this->connectivity->resolve($state),
            movement: $this->movement->resolve($state),
        );
    }
}
