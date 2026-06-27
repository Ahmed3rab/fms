<?php

namespace App\Services\Tracking\Contracts;

use App\Data\ResolvedDeviceState;

interface CurrentStateProvider
{
    public function currentState(string $vehicleUuid): ?ResolvedDeviceState;
}
