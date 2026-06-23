<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\VehicleHistoryRequest;
use App\Http\Resources\API\V1\VehicleHistoryResource;
use App\Models\Vehicle;
use App\Services\Tracking\TrackingManager;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class VehicleHistoryController extends Controller
{
    public function __invoke(VehicleHistoryRequest $request, Vehicle $vehicle, TrackingManager $tracking): AnonymousResourceCollection
    {
        return VehicleHistoryResource::collection(
            $tracking->history(
                $vehicle,
                $request->from(),
                $request->to(),
            )
        );
    }
}
