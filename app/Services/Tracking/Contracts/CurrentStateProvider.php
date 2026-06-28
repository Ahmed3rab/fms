<?php

namespace App\Services\Tracking\Contracts;

use App\Data\ResolvedDeviceState;
use App\Models\Vehicle;

interface CurrentStateProvider extends RealtimeProvider, VehicleHydrator
{
    public function currentState(Vehicle $vehicle): ?ResolvedDeviceState;
}
