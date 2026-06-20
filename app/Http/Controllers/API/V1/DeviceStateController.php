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
        $state = app(DeviceStateStore::class)->get($device->system_no);

        if ($state) {
            $state['source']  = 'realtime';
            return DeviceStateResource::make($state);
        }

        $state = $device->state;

        if ($state) {
            $state['source'] = 'database';
        }

        return DeviceStateResource::make($state);
    }
}
