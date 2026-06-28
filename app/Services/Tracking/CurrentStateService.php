<?php

namespace App\Services\Tracking;

use App\Data\ResolvedDeviceState;
use App\Models\Device;
use App\Models\Vehicle;
use App\Services\Tracking\Contracts\CurrentStateProvider;
use Illuminate\Support\Collection;

class CurrentStateService
{
    public function __construct(
        protected CurrentStateProvider $provider,
    ) {}

    public function attachCurrentState(Device $device): Device
    {
        return $this->provider->attachCurrentState($device);
    }

    /**
     * @param Collection<array-key,mixed> $devices
     * @return Collection<int,Device>
     */
    public function attachCurrentStateForMany(Collection $devices): Collection
    {
        return $this->provider->attachCurrentStateForMany($devices);
    }

    public function hydrateVehicle(Vehicle $vehicle): Vehicle
    {
        return $this->provider->hydrateVehicle($vehicle);
    }

    /**
     * @param Collection<array-key,mixed> $vehicles
     * @return Collection<int,Vehicle>
     */
    public function hydrateVehicles(Collection $vehicles): Collection
    {
        return $this->provider->hydrateVehicles($vehicles);
    }

    public function currentState(string $vehicleUuid): ?ResolvedDeviceState
    {
        return $this->provider->currentState($vehicleUuid);
    }
}
