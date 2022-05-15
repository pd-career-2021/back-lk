<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Speciality;
use App\Models\Faculty;

class SpecialityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Speciality::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $speciality = new Speciality($request->all());

        $faculty = Faculty::find($request->input('faculty_id'));
        if(!$faculty) {
            return response([
                'message' => 'Faculty not found.'
            ], 401);
        } else {
            $speciality->faculty()->associate($faculty);
        }
        
        $speciality->save();
        return $speciality;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Speciality::find($id);
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
        $speciality = Speciality::find($id);
        if ($request->has('faculty_id')) {
            $faculty = Faculty::find($request->input('faculty_id'));
            if(!$faculty) {
                return response([
                    'message' => 'Faculty not found.'
                ], 401);
            } else {
                $speciality->faculty()->associate($faculty); 
            }
        }
        
        $speciality->update($request->all());

        $speciality->save();
        return $speciality;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Speciality::destroy($id);
    }
}