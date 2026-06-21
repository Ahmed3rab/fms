<?php

namespace App\Http\Controllers\API\V1;

use App\Filters\DeviceFilter;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\DeviceResource;
use App\Models\Device;
use App\Services\Tracking\Contracts\TrackingBackend;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DeviceController extends Controller
{
    public function __construct(private TrackingBackend $tracking) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $query = (new DeviceFilter(
            Device::query()->visibleTo(auth()->user())->with('company', 'state'),
            $request->all(),
        ))->apply();

        $devices = $query->paginate();

        $this->tracking->attachCurrentStateForMany($devices->getCollection());

        return DeviceResource::collection($devices);
    }

    /**
     * @return DeviceResource
     */
    public function show(Device $device): DeviceResource
    {
        $device->load(['company', 'state']);

        return DeviceResource::make(
            $this->tracking->attachCurrentState($device)
        );
    }
}
