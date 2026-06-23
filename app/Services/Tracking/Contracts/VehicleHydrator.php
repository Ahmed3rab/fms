<?php

namespace App\Services\Tracking\Contracts;

use App\Models\Vehicle;
use Illuminate\Support\Collection;

interface VehicleHydrator
{
    public function hydrateVehicle(Vehicle $vehicle): Vehicle;

    /**
     * @param Collection<int, Vehicle> $vehicles
     * @return Collection<int, Vehicle>
     */
    public function hydrateVehicles(Collection $vehicles): Collection;
}
