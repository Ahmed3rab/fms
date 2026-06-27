<?php

namespace App\Services\Tracking\Contracts;

use App\Data\ResolvedDeviceState;

interface CurrentStateProvider extends RealtimeProvider, VehicleHydrator
{
    public function currentState(string $vehicleUuid): ?ResolvedDeviceState;
}
