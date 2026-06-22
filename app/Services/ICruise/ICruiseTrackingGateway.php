<?php

namespace App\Services\ICruise;

use App\Models\Device;
use App\Models\Vehicle;
use App\Services\Tracking\Contracts\TrackingGateway;
use App\Services\Tracking\DeviceStateStore;
use App\Services\Tracking\VehicleStatus\ConnectivityStatusResolver;
use App\Services\Tracking\VehicleStatus\MovementStatusResolver;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class ICruiseTrackingGateway implements TrackingGateway
{
    public function __construct(protected DeviceStateStore $store, protected ICruiseClient $client, protected ConnectivityStatusResolver $connectivityStatusResolver, protected MovementStatusResolver $movementStatusResolver) {}

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
            $realtimeState['source'] = 'realtime';
            $realtimeState['status'] = $this->resolveStatus($realtimeState);
            $device->setResolvedState($realtimeState);
            return;
        }

        if ($device->state) {
            $device->state->source = 'database';
            $device->state->status = $this->resolveStatus($device->state);
            $device->setResolvedState($device->state);
        }
    }

    /**
     * @param mixed[]|object $state
     */
    private function resolveStatus(array|object $state): array
    {
        return [
            'connection'    => $this->connectivityStatusResolver->resolve($state)->value,
            'movement' => $this->movementStatusResolver->resolve($state)->value,
        ];
    }

}
