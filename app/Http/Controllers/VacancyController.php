<?php

namespace App\Http\Controllers;

use App\Http\Library\ApiHelpers;
use Illuminate\Http\Request;
use App\Models\Vacancy;
use App\Models\Employer;
use App\Models\Stage;
use App\Models\Speciality;

class VacancyController extends Controller
{
    use ApiHelpers;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Vacancy::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $vacancy = new Vacancy($request->all());
        $user = $request->user();

        if ($this->isEmployer($user)) {
            $employer = Employer::find($user->id);
        } else {
            $employer = Employer::find($request->input('employer_id'));
        }

        if(!$employer) {
            return response([
                'message' => 'Employer not found.'
            ], 401);
        } else {
            $vacancy->employer()->associate($employer);
        }

        $stage = Stage::find($request->input('stage_id'));
        if(!$stage) {
            return response([
                'message' => 'Stage not found.'
            ], 401);
        } else {
            $vacancy->stage()->associate($stage);
        }

        $vacancy->save();

        $validated = array();
        foreach ($request->input('speciality_ids') as $id) {
            if (Speciality::find($id))
                array_push($validated, $id);
        }
        $vacancy->speciality()->sync($validated);

        return $vacancy;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Vacancy::find($id);
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
        $vacancy = Vacancy::find($id);
        $user = $request->user();

        if ($this->isEmployer($user)) {
            $employer_id = Employer::where('user_id', $user->id)->first()->id;
            if (Vacancy::where('id', $id)->first()->employer_id != $employer_id) {
                return response([
                    'message' => 'You do not have permission to do this.'
                ], 401);
            }
        }

        if ($this->isAdmin($user)) {
            if ($request->has('employer_id')) {
                $employer = Employer::find($request->input('employer_id'));
                if(!$employer) {
                    return response([
                        'message' => 'Employer not found.'
                    ], 401);
                } else {
                    $vacancy->employer()->associate($employer);
                }
            }
        } 
        
        if ($request->has('stage_id')) {
            $stage = Stage::find($request->input('stage_id'));
            if(!$stage) {
                return response([
                    'message' => 'Stage not found.'
                ], 401);
            } else {
                $vacancy->stage()->associate($stage);
            }
        }

        $vacancy->update($request->all());

        if ($request->has('speciality_ids')) {
            $validated = array();
            foreach ($request->input('speciality_ids') as $id) {
                if (Speciality::find($id))
                    array_push($validated, $id);
            }
            $vacancy->speciality()->sync($validated);
        }
        
        $vacancy->save();
        return $vacancy;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = $request->user();
        if ($this->isEmployer($user)) {
            $employer_id = Employer::where('user_id', $request->user()->id)->first()->id;
            if (Vacancy::where('id', $id)->first()->employer_id == $employer_id) {
                $vacancy = Vacancy::find($id);
                $vacancy->speciality()->detach();
                return Vacancy::destroy($id);
            } else {
                return response([
                    'message' => 'You do not have permission to do this.'
                ], 401);
            }
        } else if ($this->isAdmin($user)) {
            $vacancy = Vacancy::find($id);
            $vacancy->speciality()->detach();
            return Vacancy::destroy($id);
        }
    }
}