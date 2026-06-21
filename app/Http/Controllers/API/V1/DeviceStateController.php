<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\DeviceStateResource;
use App\Models\Device;
use App\Services\Tracking\Contracts\TrackingGateway;
use Illuminate\Http\Request;

class DeviceStateController extends Controller
{
    /**
     * Handle the incoming request.
     * @return DeviceStateResource
     */
    public function __invoke(Request $request, Device $device, TrackingGateway $gateway): DeviceStateResource
    {
        return DeviceStateResource::make(
            $gateway->attachCurrentState($device)->current_state
        );
    }
}
