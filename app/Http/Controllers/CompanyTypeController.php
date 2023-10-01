<?php

namespace App\Http\Controllers;

use App\Http\Resources\CompanyTypeCollection;
use App\Http\Resources\CompanyTypeResource;
use Illuminate\Http\Request;
use App\Models\CompanyType;

class CompanyTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): CompanyTypeCollection
    {
        return new CompanyTypeCollection(CompanyType::all());
    }

   
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): CompanyTypeResource
    {
        $validated = $request->validate([
            'title' => 'required|string|max:45'
        ]);

        $companyType = CompanyType::create($validated);

        return new CompanyTypeResource($companyType);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(CompanyType $companyType): CompanyTypeResource
    {
        return new CompanyTypeResource($companyType);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CompanyType $companyType): CompanyTypeResource
    {
        $validated = $request->validate([
            'title' => 'string|max:45'
        ]);

        $companyType->update($request->all());

        return new CompanyTypeResource($companyType);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(CompanyType $companyType)
    {
        return $companyType->delete();
    }
}
