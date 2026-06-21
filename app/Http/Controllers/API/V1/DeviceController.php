<?php

namespace App\Http\Controllers\API\V1;

use App\Filters\DeviceFilter;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\DeviceResource;
use App\Models\Device;
use App\Services\Tracking\Contracts\TrackingGateway;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DeviceController extends Controller
{
    public function __construct(private TrackingGateway $gateway) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $query = (new DeviceFilter(
            Device::query()->visibleTo(auth()->user())->with('vehicle.company', 'state'),
            $request->all(),
        ))->apply();

        $devices = $query->paginate();

        $this->gateway->attachCurrentStateForMany($devices->getCollection());

        return DeviceResource::collection($devices);
    }

    /**
     * @return DeviceResource
     */
    public function show(Device $device): DeviceResource
    {
        $device->load(['vehicle.company', 'state']);

        return DeviceResource::make(
            $this->gateway->attachCurrentState($device)
        );
    }
}
