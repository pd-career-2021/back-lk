<?php

namespace App\Http\Controllers;

use App\Http\Library\ApiHelpers;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User;
use App\Models\Speciality;

class StudentController extends Controller
{
    use ApiHelpers;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Student::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $student = new Student($request->all());
        $user = User::find($request->input('user_id'));
        if ($user) {
            if ($user->student()->exists() || $user->employer()->exists()) {
                return response([
                    'message' => 'User already associated.'
                ], 401);
            } else {
                $student->user()->associate($user);
            }
        }

        $student->user()->associate($user);
        $student->save();

        $validated = array();
        foreach ($request->input('speciality_ids') as $id) {
            if (Speciality::find($id))
                array_push($validated, $id);
        }
        $student->speciality()->sync($validated);

        return $student;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Student::find($id);
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
        $student = Student::find($id);
        $user = $request->user();
        if ($this->isStudent($user)) {
            $student_id = Student::where('user_id', $user->id)->first()->id;
            if ($id == $student_id) {
                $student->update($request->all());

                $student->save();
                return $student;
            } else {
                return response([
                    'message' => 'You do not have permission to do this.'
                ], 401);
            }
        }
        
        if ($request->has('user_id')) {
            $user = User::find($request->input('user_id'));
            if ($user) {
                if ($user->student()->exists() || $user->employer()->exists()) {
                    return response([
                        'message' => 'User already associated.'
                    ], 401);
                } else {
                    $student->user()->associate($user);
                }
            }
        }
                
        $student->update($request->all());

        if ($request->has('speciality_ids')) {
            $validated = array();
            foreach ($request->input('speciality_ids') as $id) {
                if (Speciality::find($id))
                    array_push($validated, $id);
            }
            $student->speciality()->sync($validated);
        }
        
        $student->save();
        return $student;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $student = Student::find($id);
        $student->speciality()->detach();

        return Student::destroy($id);
    }
}