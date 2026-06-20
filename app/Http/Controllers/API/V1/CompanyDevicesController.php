<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\DeviceResource;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyDevicesController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Company $company)
    {
        abort_unless(
            Company::query()
                ->visibleTo($request->user())
                ->whereKey($company->id)
                ->exists(),
            404
        );

        return DeviceResource::collection(
            $company->devices()
                ->with(['company', 'state'])
                ->paginate()
        );
    }
}
