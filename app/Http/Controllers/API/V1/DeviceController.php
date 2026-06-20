<?php

namespace App\Http\Controllers\API\V1;

use App\Filters\DeviceFilter;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\DeviceResource;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DeviceController extends Controller
{
    /**
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $devices = (new DeviceFilter(
            Device::query()->visibleTo(auth()->user())->with('company'),
            $request->all(),
        ))
            ->apply()
            ->paginate();
        return DeviceResource::collection($devices);
    }

    /**
     * @return DeviceResource
     */
    public function show(Device $device): DeviceResource
    {
        $device->load('company');

        return DeviceResource::make($device);
    }
}
