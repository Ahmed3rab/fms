<?php

namespace App\Services\Tracking\Contracts;

use App\Models\Device;
use Illuminate\Support\Collection;

interface RealtimeProvider
{
    public function attachCurrentState(Device $device): Device;

    /**
     * @param Collection<int, Device> $devices
     * @return Collection<int, Device>
     */
    public function attachCurrentStateForMany(Collection $devices): Collection;
}
