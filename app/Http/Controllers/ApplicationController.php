<?php

namespace App\Http\Controllers;

use App\Http\Library\ApiHelpers;
use App\Http\Resources\ApplicationCollection;
use App\Http\Resources\ApplicationResource;
use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\ApplicationStatus;
use App\Models\Employer;
use App\Models\Student;
use App\Models\Vacancy;
use Illuminate\Support\Facades\Validator;

class ApplicationController extends Controller
{
    use ApiHelpers;
    public function index(): ApplicationCollection
    {
        return new ApplicationCollection(Application::all());
    }

    public function indexStudentApplications(Request $request): ApplicationCollection
    {
        return new ApplicationCollection(Application::where(
            'student_id',
            Student::where(
                'user_id',
                $request->user()->id
            )->first()->id
        )->get());
    }

    public function indexVacanciesApplications(Request $request): ApplicationCollection
    {
        $user = $request->user();
        $employer = $user->employer;
    
        if (!$employer) {
            return response(['message' => 'You are not associated with any employer.'], 401);
        }
    
        $vacancyIds = $employer->vacancies->pluck('id');
    
        $applications = Application::whereIn('vacancy_id', $vacancyIds)->get();
    
        return new ApplicationCollection($applications);
    }

    public function store(Request $request): ApplicationResource
    {
        $validated = $request->validate([
            'desc' => 'required|string|max:1000',
            'student_id' => 'integer',
            'vacancy_id' => 'required|integer',
            'application_status_id' => 'required|integer',
        ]);

        $application = Application::create($validated);
        return new ApplicationResource($application);

        if ($this->isStudent($user)) {
            $student = Student::where('user_id', $user->id)->first();
        } else {
            $student = Student::find($request->input('student_id'));
        }

        if (!$student) {
            return response([
                'message' => 'Student not found.'
            ], 401);
        } else {
            $application->student()->associate($student);
        }

        $vacancy = Vacancy::find($request->input('vacancy_id'));
        if (!$vacancy) {
            return response([
                'message' => 'Vacancy not found.'
            ], 401);
        } else {
            $application->vacancy()->associate($vacancy);
        }

        $application_status = ApplicationStatus::find($request->input('application_status_id'));
        if (!$application_status) {
            return response([
                'message' => 'Application status not found.'
            ], 401);
        } else {
            $application->application_status()->associate($application_status);
        }

        $application->save();

        return new ApplicationResource($application);
    }

    public function show(Application $application): ApplicationStatusResource
    {
        return new  ApplicationResource($application);
    }

    public function update(Request $request, Application $application): ApplicationResource
    {
        $validated = $request->validate([
            'desc' => 'string|max:1000',
            'student_id' => 'integer',
            'vacancy_id' => 'integer',
            'application_status_id' => 'integer',
        ]);

        $application->update($validated);

        return new ApplicationResource($application);
        
        {
            $user = $request->user();
            $application = Application::findOrFail($id);
        
            if ($this->isEmployer($user)) {
                $employer = $user->employer;
        
                if (!$employer || $employer->id !== $application->vacancy->employer_id) {
                    return response(['message' => 'You do not have permission to do this.'], 401);
                }
        
                if ($request->has('application_status_id')) {
                    $application->update(['application_status_id' => $request->input('application_status_id')]);
                }
            } else {
                $application->fill($request->only(['desc', 'student_id', 'vacancy_id']));
        
                if ($request->has('student_id')) {
                    $application->student()->associate(Student::findOrFail($request->input('student_id')));
                }
        
                if ($request->has('vacancy_id')) {
                    $application->vacancy()->associate(Vacancy::findOrFail($request->input('vacancy_id')));
                }
        
                if ($request->has('application_status_id')) {
                    $application->application_status()->associate(ApplicationStatus::findOrFail($request->input('application_status_id')));
                }
        
                $application->save();
            }
        
            return new ApplicationResource($application);
        }

    public function destroy(Request $request, Application $application)
    {
        $user = $request->user();
        if ($this->isStudent($user)) {
            $student_id = Student::where('user_id', $request->user()->id)->first()->id;
            if (Application::where('id', $id)->first()->student_id == $student_id) {
                return $application->delete();
            } else {
                return response([
                    'message' => 'You do not have permission to do this.'
                ], 401);
            }
        } else if ($this->isAdmin($user)) {
            return $application->delete();
        }
    }
}
