<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApplicationStatusCollection;
use App\Http\Resources\ApplicationStatusResource;
use Illuminate\Http\Request;
use App\Models\ApplicationStatus;
use Illuminate\Support\Facades\Validator;

class ApplicationStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new ApplicationStatusCollection(ApplicationStatus::all());
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
            'name' => 'required|string|max:45',
            'desc' => 'required|string|max:1000'
        ]);

        if ($validator->fails()) {
            return $validator->errors()->all();
        }

        $applicationStatus = new ApplicationStatus($request->all());
        $applicationStatus->save();

        return new ApplicationStatusResource($applicationStatus);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new ApplicationStatusResource(ApplicationStatus::find($id));
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
            'name' => 'required|string|max:45',
            'desc' => 'required|string|max:1000'
        ]);

        if ($validator->fails()) {
            return $validator->errors()->all();
        }
        
        $applicationStatus = ApplicationStatus::find($id);
        $applicationStatus->update($request->all())->save();

        return new ApplicationStatusResource($applicationStatus);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return ApplicationStatus::destroy($id);
    }
}