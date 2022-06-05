<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VacancyFunction;
use Illuminate\Support\Facades\Validator;

class VacancyFunctionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return VacancyFunction::all();
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

        $vacancyFunction = new VacancyFunction($request->all());
        $vacancyFunction->save();

        return $vacancyFunction;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return VacancyFunction::find($id);
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
        
        $vacancyFunction = VacancyFunction::find($id);
        $vacancyFunction->update($request->all());
        $vacancyFunction->save();

        return $vacancyFunction;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return VacancyFunction::destroy($id);
    }
}
