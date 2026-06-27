<?php

namespace App\Services\Tracking;

use App\Data\ResolvedDeviceState;
use App\Models\Device;
use Illuminate\Support\Collection;

class CurrentStateResolver
{
    public function __construct(
        protected DeviceStateStore $store,
        protected StateResolver $resolver
    ) {}

    public function resolve(Device $device): void
    {
        $device->loadMissing('state');

        $realtimeState = $this->store->get($device->uuid);

        $this->apply($device, $realtimeState);
    }

    /**
     * @param Collection<int, Device> $devices
     */
    public function resolveMany(Collection $devices): void
    {
        $states = $this->store->many(
            $devices->pluck('uuid')->all()
        );

        $devices->each(function (Device $device) use ($states) {
            $this->apply(
                $device,
                $states[$device->uuid] ?? null,
            );
        });
    }

    private function apply(Device $device, ?ResolvedDeviceState $resolvedDeviceState): void
    {
        $device->setResolvedState($resolvedDeviceState);
    }
}
