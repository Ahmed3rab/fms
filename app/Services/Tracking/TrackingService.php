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

        if ($state) {
            $state['source'] = 'realtime';

            $device->setResolvedState($state);

            return $device;
        }

        if ($device->state) {
            $device->state->source = 'database';

            $device->setResolvedState($device->state);
        }

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

            if ($state) {
                $state['source'] = 'realtime';

                $device->setResolvedState($state);

                return;
            }

            if ($device->state) {
                $device->state->source = 'database';

                $device->setResolvedState($device->state);
            }
        });

        return $devices;
    }
}
