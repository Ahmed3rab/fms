<?php

namespace App\Services\Tracking\Contracts;

use App\Models\Device;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface TrackingBackend
{
    public function attachCurrentState(Device $device): Device;

    /**
     * @param Collection<array-key,Model> $devices
     */
    public function attachCurrentStateForMany(Collection $devices): Collection;
}
