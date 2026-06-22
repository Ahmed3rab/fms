<?php

namespace App\Services\ICruise;

use App\Data\ResolvedDeviceState;
use App\Models\Device;
use App\Models\Vehicle;
use App\Services\Tracking\Contracts\TrackingGateway;
use App\Services\Tracking\DeviceStateStore;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class ICruiseTrackingGateway implements TrackingGateway
{
    public function __construct(protected DeviceStateStore $store, protected ICruiseClient $client) {}

    public function attachCurrentState(Device $device): Device
    {
        $device->loadMissing('state');

        $state = $this->store->get($device->system_no);

        $this->applyCurrentState($device, $state);

        return $device;
    }

    /**
     * @param Collection<array-key,Model> $devices
     * @return Collection<array-key,Model>
     */
    public function attachCurrentStateForMany(Collection $devices): Collection
    {
        $states = $this->store->many($devices->pluck('system_no')->all());

        $devices->each(function (Device $device) use ($states) {

            $state = $states[$device->system_no] ?? null;

            $this->applyCurrentState($device, $state);
        });

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

    public function history(Vehicle $vehicle, Carbon $from, Carbon $to): Collection
    {
        $history = $this->client->history(
            $vehicle->device->icruise_product_id,
            $from,
            $to,
        );

        return collect($history['Data'] ?? []);
    }

    /**
     * @return void
     */
    private function applyCurrentState(Device $device, ?array $realtimeState): void
    {
        if ($realtimeState) {
            $device->setResolvedState(
                ResolvedDeviceState::fromRealtime($realtimeState)
            );

            return;
        }

        if ($device->state) {
            $device->setResolvedState(
                ResolvedDeviceState::fromDatabase($device->state)
            );
        }
    }
}
