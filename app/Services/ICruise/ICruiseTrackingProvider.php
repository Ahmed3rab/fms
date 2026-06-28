<?php

namespace App\Services\ICruise;

use App\Data\History;
use App\Data\ResolvedDeviceState;
use App\Models\Device;
use App\Models\Vehicle;
use App\Services\ICruise\Mappers\HistoryMapper;
use App\Services\Tracking\Contracts\TrackingProvider;
use App\Services\Tracking\CurrentStateResolver;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class ICruiseTrackingProvider implements TrackingProvider
{
    public function __construct(
        protected CurrentStateResolver $resolver,
        protected ICruiseClient $client,
        protected HistoryMapper $historyMapper,
    ) {}

    public function attachCurrentState(Device $device): Device
    {
        $this->resolver->resolve($device);
        return $device;
    }

    /**
     * @param Collection<array-key,Model> $devices
     * @return Collection<array-key,Model>
     */
    public function attachCurrentStateForMany(Collection $devices): Collection
    {
        $this->resolver->resolveMany($devices);

        return $devices;
    }

    public function hydrateVehicle(Vehicle $vehicle): Vehicle
    {
        $vehicle->loadMissing('device.state');

        if (! $vehicle->device) {
            return $vehicle;
        }

        $this->attachCurrentState($vehicle->device);

        return $vehicle;
    }

    public function hydrateVehicles(Collection $vehicles): Collection
    {
        $devices = $vehicles
               ->pluck('device')
               ->filter();

        $this->attachCurrentStateForMany($devices);

        return $vehicles;
    }

    public function history(Vehicle $vehicle, Carbon $from, Carbon $to): History
    {
        $history = $this->client->history(
            $vehicle->device->icruise_product_id,
            $from,
            $to,
        );
        return $this->historyMapper->map($history['Data'] ?? []);
    }

    public function currentState(Vehicle $vehicle): ?ResolvedDeviceState
    {
        $vehicle->loadMissing('device.state');

        if (! $vehicle->device) {
            return null;
        }

        $this->attachCurrentState($vehicle->device);

        return $vehicle->device->current_state;
    }
}
