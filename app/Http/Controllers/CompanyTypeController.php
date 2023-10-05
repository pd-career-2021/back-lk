<?php

namespace App\Http\Controllers;

use App\Http\Resources\CompanyTypeCollection;
use App\Http\Resources\CompanyTypeResource;
use Illuminate\Http\Request;
use App\Models\CompanyType;

class CompanyTypeController extends Controller
{
    public function index(): CompanyTypeCollection
    {
        return new CompanyTypeCollection(CompanyType::all());
    }

    public function store(Request $request): CompanyTypeResource
    {
        $validated = $request->validate([
            'title' => 'required|string|max:45'
        ]);

        $companyType = CompanyType::create($validated);

        return new CompanyTypeResource($companyType);
    }

    public function show(CompanyType $companyType): CompanyTypeResource
    {
        return new CompanyTypeResource($companyType);
    }

    public function update(Request $request, CompanyType $companyType): CompanyTypeResource
    {
        $validated = $request->validate([
            'title' => 'string|max:45'
        ]);

        $companyType->update($validated);

        return new CompanyTypeResource($companyType);
    }

    public function destroy(CompanyType $companyType)
    {
        return $companyType->delete();
    }
}
