<?php

namespace App\Http\Controllers;

use App\Http\Resources\CompanyTypeCollection;
use App\Http\Resources\CompanyTypeResource;
use Illuminate\Http\Request;
use App\Models\CompanyType;
use Illuminate\Support\Facades\Validator;

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
        // $validator = Validator::make($request->all(), [
        //     'title' => 'required|string|max:45'
        // ]);
        $validated = $request->validate([
            'title' => 'required|string|max:45'
        ]);

        if ($validator->fails()) {
            return $validator->errors()->all();
        }

        $companyType = new CompanyType($request->all());

        $companyType->save();

        return new CompanyTypeResource($companyType);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): CompanyTypeResource
    {
        return new CompanyTypeResource(CompanyType::find($id));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id): CompanyTypeResource
    {
        // $validator = Validator::make($request->all(), [
        //     'title' => 'string|max:45'
        // ]);
        $validated = $request->validate([
            'title' => 'string|max:45'
        ]);

        if ($validator->fails()) {
            return $validator->errors()->all();
        }

        $companyType = CompanyType::find($id);
        $companyType->update($request->all())->save();

        return new CompanyTypeResource($companyType);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return CompanyType::destroy($id);
    }
}
