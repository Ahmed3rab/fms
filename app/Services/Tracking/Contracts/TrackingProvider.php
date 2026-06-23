<?php

namespace App\Services\Tracking\Contracts;

use App\Models\Device;
use App\Models\Vehicle;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

interface TrackingProvider extends RealtimeProvider, HistoryProvider, VehicleHydrator
{
    public function attachCurrentState(Device $device): Device;

    /**
     * @param Collection<int,Device> $devices
     */
    public function attachCurrentStateForMany(Collection $devices): Collection;

    public function hydrateVehicle(Vehicle $vehicle): Vehicle;

    /**
     * @param Collection<int,Vehicle> $vehicles
     */
    public function hydrateVehicles(Collection $vehicles): Collection;

    public function history(Vehicle $vehicle, Carbon $from, Carbon $to): Collection;
}
