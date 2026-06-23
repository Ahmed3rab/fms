<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\CompanyResource;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        return CompanyResource::collection(
            Company::query()
                ->visibleTo($request->user())
                ->withCount('vehicles')
                ->paginate()
        );
    }

    public function show(Request $request, Company $company)
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
