<?php

namespace App\Services\Tracking;

use App\Data\Factories\ResolvedDeviceStateFactory;
use App\Data\RealtimeDeviceState;
use App\Data\ResolvedDeviceState;
use App\Enums\DeviceStateSource;
use App\Models\DeviceState;

class StateResolver
{
    public function __construct(protected ResolvedDeviceStateFactory $factory) {}

    public function realtime(RealtimeDeviceState $state): ResolvedDeviceState
    {
        return $this->factory->make(
            $state,
            DeviceStateSource::Realtime,
        );
    }

    public function database(DeviceState $state): ResolvedDeviceState
    {
        return $this->factory->make(
            $state,
            DeviceStateSource::Database,
        );
    }
}
