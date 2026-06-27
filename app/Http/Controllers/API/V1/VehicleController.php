<?php

namespace App\Http\Controllers\API\V1;

use App\Filters\VehicleFilter;
use App\Http\Resources\API\V1\VehicleResource;
use App\Models\Vehicle;
use App\Http\Controllers\Controller;
use App\Services\Tracking\CurrentStateService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class VehicleController extends Controller
{
    public function __construct(private CurrentStateService $currentStateService) {}

    public function index(Request $request): AnonymousResourceCollection
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

        $this->currentStateService->hydrateVehicles(
            $vehicles->getCollection()
        );

        return VehicleResource::collection($vehicles);
    }

    public function show(Vehicle $vehicle): VehicleResource
    {
        $vehicle->load([
            'company',
            'device.state',
        ]);

        return VehicleResource::make(
            $this->currentStateService->hydrateVehicle($vehicle)
        );
    }
}
