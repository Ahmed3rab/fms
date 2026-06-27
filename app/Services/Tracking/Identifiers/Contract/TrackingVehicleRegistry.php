<?php

namespace App\Services\Tracking\Identifiers\Contract;

use App\Models\Device;
use Illuminate\Support\Collection;

interface TrackingVehicleRegistry
{
    public function uuidFromDevice(string $deviceUuid): string;

    /**
    * @param Collection<int,Device> $devices
    */
    public function synchronize(Collection $devices): void;
}
