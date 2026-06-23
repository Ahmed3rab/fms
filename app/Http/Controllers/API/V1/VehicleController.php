<?php

namespace App\Http\Controllers\API\V1;

use App\Filters\VehicleFilter;
use App\Http\Resources\API\V1\VehicleResource;
use App\Models\Vehicle;
use App\Http\Controllers\Controller;
use App\Services\Tracking\TrackingManager;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class VehicleController extends Controller
{
    public function index(Request $request, TrackingManager $tracking): AnonymousResourceCollection
    {
        $query = (new VehicleFilter(
            Vehicle::query()
                ->visibleTo(auth()->user())
                ->with([
                    'company',
                    'device.state',
                ]),
            $request->all(),
        ))->apply();

        $vehicles = $query->paginate();

        $tracking->hydrateVehicles(
            $vehicles->getCollection()
        );

        return VehicleResource::collection($vehicles);
    }

    public function show(Vehicle $vehicle, TrackingManager $tracking): VehicleResource
    {
        $vehicle->load([
            'company',
            'device.state',
        ]);

        return VehicleResource::make(
            $tracking->hydrateVehicle($vehicle)
        );
    }
}
