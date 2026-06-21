<?php

namespace App\Services\Tracking\Contracts;

use App\Models\Device;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface TrackingBackend
{
    public function resolve(Device $device): Device;

    /**
     * @param Collection<array-key,Model> $devices
     */
    public function resolveMany(Collection $devices): Collection;
}
