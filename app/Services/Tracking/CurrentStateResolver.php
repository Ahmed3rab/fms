<?php

namespace App\Services\Tracking;

use App\Data\Factories\ResolvedDeviceStateFactory;
use App\Data\RealtimeDeviceState;
use App\Enums\DeviceStateSource;
use App\Models\Device;
use Illuminate\Support\Collection;

class CurrentStateResolver
{
    public function __construct(
        protected DeviceStateStore $store,
        protected ResolvedDeviceStateFactory $factory,
    ) {}

    public function resolve(Device $device): void
    {
        $device->loadMissing('state');

        $realtimeState = $this->store->get($device->system_no);

        $this->apply($device, $realtimeState);
    }

    /**
     * @param Collection<int, Device> $devices
     */
    public function resolveMany(Collection $devices): void
    {
        $states = $this->store->many(
            $devices->pluck('system_no')->all()
        );

        $devices->each(function (Device $device) use ($states) {
            $this->apply(
                $device,
                $states[$device->system_no] ?? null,
            );
        });
    }

    private function apply(Device $device, ?RealtimeDeviceState $realtimeState): void
    {
        if ($realtimeState) {
            $device->setResolvedState(
                $this->factory->make(
                    $realtimeState,
                    DeviceStateSource::Realtime,
                )
            );

            return;
        }

        if ($device->state) {
            $device->setResolvedState(
                $this->factory->make(
                    $device->state,
                    DeviceStateSource::Database,
                )
            );
        }
    }
}
