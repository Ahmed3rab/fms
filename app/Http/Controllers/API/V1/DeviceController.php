<?php

namespace App\Http\Controllers\API\V1;

use App\Filters\DeviceFilter;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\DeviceResource;
use App\Models\Device;
use App\Services\Tracking\DeviceStateStore;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DeviceController extends Controller
{
    /**
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = (new DeviceFilter(
            Device::query()
                ->visibleTo(auth()->user())
                ->with('company', 'state'),
            $request->all(),
        ))
            ->apply();

        $devices = $query->paginate();
        $states = app(DeviceStateStore::class)->many(
            $devices->getCollection()
                ->pluck('system_no')
                ->all(),
        );
        $devices->getCollection()->each(function (Device $device) use ($states) {
            $state = $states[$device->system_no] ?? null;

            if ($state) {
                $state['source'] = 'realtime';

                $device->setResolvedState($state);

                return;
            }

            if ($device->state) {
                $device->state->source = 'database';

                $device->setResolvedState($device->state);
            }
        });
        return DeviceResource::collection($devices);
    }

    /**
     * @return DeviceResource
     */
    public function show(Device $device): DeviceResource
    {
        $device->load(['company', 'state']);
        $state = app(DeviceStateStore::class)
            ->getByDevice($device);
        if ($state) {
            $state['source'] = 'realtime';

            $device->setResolvedState($state);
        } else {
            if ($device->state) {
                $device->state->source = 'database';

                $device->setResolvedState($device->state);
            }
        }

        return DeviceResource::make($device);
    }
}
