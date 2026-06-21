<?php

namespace App\Services\Tracking;

use App\Models\Device;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class TrackingService
{
    public function __construct(protected DeviceStateStore $store) {}

    public function resolve(Device $device): Device
    {
        $device->loadMissing('state');

        $state = $this->store->getByDevice($device);

        $this->attachResolvedState($device, $state);

        return $device;
    }

    /**
     * @param Collection<array-key,Model> $devices
     * @return Collection<array-key,Model>
     */
    public function resolveMany(Collection $devices): Collection
    {
        $states = $this->store->many($devices->pluck('system_no')->all());

        $devices->each(function (Device $device) use ($states) {

            $state = $states[$device->system_no] ?? null;

            $this->attachResolvedState($device, $state);
        });

        return $devices;
    }

    /**
     * @return void
     */
    public function attachResolvedState(Device $device, ?array $realtimeState): void
    {
        if ($realtimeState) {
            $realtimeState['source'] = 'realtime';
            $device->setResolvedState($realtimeState);
            return;
        }

        if ($device->state) {
            $device->state->source = 'database';
            $device->setResolvedState($device->state);
        }
    }
}
