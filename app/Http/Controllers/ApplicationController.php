<?php

namespace App\Http\Controllers;

use App\Http\Library\ApiHelpers;
use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\ApplicationStatus;
use App\Models\Vacancy;
use App\Models\Student;
use App\Models\Employer;
use Illuminate\Support\Facades\Validator;

class ApplicationController extends Controller
{
    use ApiHelpers;
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $applications = Application::all();
        foreach($applications as $application) {
            $application['student'] = $application->student()->get();
            $application['vacancy'] = $application->vacancy()->get();
            $application['application_status'] = $application->application_status()->get();
        }
        
        return response()->json($applications);
    }
    
    public function indexStudentApplications(Request $request)
    {
        return Application::where('student_id', Student::where('user_id', $request->user()->id)->first()->id)->get();
    }
    
    public function indexVacanciesApplications(Request $request)
    {
        $employer_id = Employer::where('user_id', $request->user()->id)->first()->id;
        $vacancy_ids = array();
        foreach (Vacancy::where('employer_id', $employer_id)->get() as $vacancy) {
            array_push($vacancy_ids, $vacancy->id);
        }
        $applications = array();
        foreach ($vacancy_ids as $id) {
            array_push($applications, Application::where('vacancy_id', $id)->get());
        }
        
        return $applications;
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
            'student_id' => 'required|integer',
            'vacancy_id' => 'required|integer',
            'application_status_id' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return $validator->errors()->all();
        }
        
        $application = new Application($request->all());
        $user = $request->user();
        if ($this->isStudent($user)) {
            $student = Student::find($user->id);
        } else {
            $student = Student::find($request->input('student_id'));
        }
        
        if(!$student) {
            return response([
                'message' => 'Student not found.'
            ], 401);
        } else {
            $application->student()->associate($student);
        }
        
        $vacancy = Vacancy::find($request->input('vacancy_id'));
        if(!$vacancy) {
            return response([
                'message' => 'Vacancy not found.'
            ], 401);
        } else {
            $application->vacancy()->associate($vacancy);
        }
        
        $application_status = ApplicationStatus::find($request->input('application_status_id'));
        if(!$application_status) {
            return response([
                'message' => 'Application status not found.'
            ], 401);
        } else {
            $application->application_status()->associate($application_status);
        }
        
        $application->save();
        return $application;
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $application = Application::find($id);
        $application['student'] = $application->student()->get();
        $application['vacancy'] = $application->vacancy()->get();
        $application['application_status'] = $application->application_status()->get();
        
        return $application;
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
            'student_id' => 'integer',
            'vacancy_id' => 'integer',
            'application_status_id' => 'integer',
        ]);
        if ($validator->fails()) {
            return $validator->errors()->all();
        }
        
        $user = $request->user();
        
        if ($this->isEmployer($user)) {
            $employer_id = Employer::where('user_id', $user->id)->first()->id;
            $vacancy_id = Application::where('id', $id)->first()->vacancy_id;
            if (Vacancy::where('id', $vacancy_id)->first()->employer_id != $employer_id) {
                return response([
                    'message' => 'You do not have permission to do this.'
                ], 401);
            } else {
                $application = Application::find($id);
                if ($request->has('application_status_id')) {
                    $application_status = ApplicationStatus::find($request->input('application_status_id'));
                    if(!$application_status) {
                        return response([
                            'message' => 'Application status not found.'
                        ], 401);
                    } else {
                        $application->application_status()->associate($application_status);
                        $application->save();
                        return $application;
                    }
                }
            }
        }
        
        $application = Application::find($id);
        if ($request->has('student_id')) {
            $student = Student::find($request->input('student_id'));
            if(!$student) {
                return response([
                    'message' => 'Student not found.'
                ], 401);
            } else {
                $application->student()->associate($student);
            }
        }
        
        if ($request->has('vacancy_id')) {
            $vacancy = Vacancy::find($request->input('vacancy_id'));
            if(!$vacancy) {
                return response([
                    'message' => 'Vacancy not found.'
                ], 401);
            } else {
                $application->vacancy()->associate($vacancy);
            }
        }
        
        if ($request->has('application_status_id')) {
            $application_status = ApplicationStatus::find($request->input('application_status_id'));
            if(!$application_status) {
                return response([
                    'message' => 'Application status not found.'
                ], 401);
            } else {
                $application->application_status()->associate($application_status);
            }
        }
        
        $application->update($request->all());
        
        $application->save();
        $application['student'] = $application->student()->get();
        $application['vacancy'] = $application->vacancy()->get();
        $application['application_status'] = $application->application_status()->get();
        return $application;
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        if ($this->isStudent($user)) {
            $student_id = Student::where('user_id', $request->user()->id)->first()->id;
            if (Application::where('id', $id)->first()->student_id == $student_id) {
                return Application::destroy($id);
            } else {
                return response([
                    'message' => 'You do not have permission to do this.'
                ], 401);
            }
        } else if ($this->isAdmin($user)) {
            return Application::destroy($id);
        } 
    }
}