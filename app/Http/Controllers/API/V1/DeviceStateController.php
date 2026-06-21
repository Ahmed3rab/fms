<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\DeviceStateResource;
use App\Models\Device;
use App\Services\Tracking\DeviceStateStore;
use Illuminate\Http\Request;

class DeviceStateController extends Controller
{
    /**
     * Handle the incoming request.
     * @return DeviceStateResource
     */
    public function __invoke(Request $request, Device $device): DeviceStateResource
    {
        $device->load('state');

        $state = app(DeviceStateStore::class)
            ->getByDevice($device);

        if ($state) {
            $state['source'] = 'realtime';

            $device->setResolvedState($state);
        } elseif ($device->state) {
            $device->state->source = 'database';

            $device->setResolvedState($device->state);
        }

        return DeviceStateResource::make($device->current_state);
    }
}
