<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\CompanyResource;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CompanyController extends Controller
{
    /**
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        return CompanyResource::collection(
            Company::query()
                ->visibleTo($request->user())
                ->withCount('vehicles')
                ->paginate()
        );
    }
    /**
     * @return CompanyResource
     */
    public function show(Request $request, Company $company): CompanyResource
    {
        abort_unless(
            Company::query()
                ->visibleTo($request->user())
                ->whereKey($company->id)
                ->exists(),
            404
        );

        return CompanyResource::make(
            $company->loadCount('vehicles')
        );
    }
}
