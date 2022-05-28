<?php

namespace App\Http\Controllers;

use App\Http\Library\ApiHelpers;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        $students = Student::all();
        foreach ($students as $student) {
            $student->user;
        }

        return $students;
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
            'desc' => 'required|string|max:1000',
            'user_id' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return $validator->errors()->all();
        }

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
        $student->save();
        $student->user;

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
        $student = Student::find($id);
        $student->user;

        return $student;
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
            'desc' => 'string|max:1000',
            'user_id' => 'integer',
        ]);
        if ($validator->fails()) {
            return $validator->errors()->all();
        }

        $student = Student::find($id);
        $user = $request->user();

        if ($this->isStudent($user)) {
            $student_id = Student::where('user_id', $user->id)->first()->id;
            if ($id == $student_id) {
                $student->update($request->all());
                $student->save();
                $student->user;
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
        $student->save();
        $student->user;

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
        return Student::destroy($id);
    }
}
