<?php

namespace App\Services\Tracking\Contracts;

use App\Models\Device;
use App\Models\Vehicle;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

interface TrackingGateway
{
    public function attachCurrentState(Device $device): Device;

    /**
     * @param Collection<array-key,Model> $devices
     */
    public function attachCurrentStateForMany(Collection $devices): Collection;

    public function hydrateVehicle(Vehicle $vehicle): Vehicle;

    /**
     * @param Collection<array-key, Vehicle> $vehicles
     * @return Collection<array-key, Vehicle>
     */
    public function hydrateVehicles(Collection $vehicles): Collection;

    /**
     * @return void
     */
    public function history(Vehicle $vehicle, Carbon $from, Carbon $to): Collection;
}
