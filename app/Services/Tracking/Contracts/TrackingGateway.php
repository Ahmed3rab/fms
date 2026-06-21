<?php

namespace App\Services\Tracking\Contracts;

use App\Models\Device;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

interface TrackingGateway
{
    public function attachCurrentState(Device $device): Device;

    /**
     * @param Collection<array-key,Model> $devices
     */
    public function attachCurrentStateForMany(Collection $devices): Collection;

    /**
     * @return void
     */
    public function history(Device $device, Carbon $from, Carbon $to): Collection;
}
