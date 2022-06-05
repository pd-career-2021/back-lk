<?php

namespace App\Http\Controllers;

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
    public function index()
    {
        $companyType = CompanyType::all();
        return $companyType;
    }

   
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:45'
        ]);

        if ($validator->fails()) {
            return $validator->errors()->all();
        }

        $companyType = new CompanyType($request->all());

        $companyType->save();

        return $companyType;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $companyType = CompanyType::find($id);
        return $companyType;
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'string|max:45'
        ]);

        if ($validator->fails()) {
            return $validator->errors()->all();
        }

        $companyType = CompanyType::find($id);
        $companyType->update($request->all());
        $companyType->save();

        return $companyType;
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
