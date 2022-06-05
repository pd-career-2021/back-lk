<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VacancyType;
use Illuminate\Support\Facades\Validator;

class VacancyTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return VacancyType::all();
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
            'title' => 'required|string|max:64'
        ]);

        if ($validator->fails()) {
            return $validator->errors()->all();
        }

        $vacancyType = new VacancyType($request->all());

        $vacancyType->save();

        return $vacancyType;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return VacancyType::find($id);
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
            'title' => 'required|string|max:64'
        ]);

        if ($validator->fails()) {
            return $validator->errors()->all();
        }

        $vacancyType = VacancyType::find($id);
        $vacancyType->update($request->all());
        $vacancyType->save();

        return $vacancyType;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return VacancyType::destroy($id);
    }
}
