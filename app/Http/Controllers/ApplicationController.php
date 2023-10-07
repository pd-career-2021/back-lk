<?php

namespace App\Http\Controllers;

use App\Http\Library\ApiHelpers;
use App\Http\Resources\ApplicationCollection;
use App\Http\Resources\ApplicationResource;
use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\ApplicationStatus;
use App\Models\Student;
use App\Models\Vacancy;

class ApplicationController extends Controller
{
    use ApiHelpers;
    public function index(): ApplicationCollection
    {
        return new ApplicationCollection(Application::all());
    }

    public function indexStudentApplications(Request $request): ApplicationCollection
    {
        $student = $request->user()->student;

        if (!$student) {
            return response()->json(['message' => 'You are not associated with any student.'], 404);
        }

        $applications = Application::where('student_id', $student->id)->get();

        return new ApplicationCollection($applications);
    }

    public function indexVacanciesApplications(Request $request): ApplicationCollection
    {
        $employer = $request->user()->employer;

        if (!$employer) {
            return response()->json(['message' => 'You are not associated with any employer.'], 404);
        }

        $vacancyIds = Vacancy::where('employer_id', $employer->id)->pluck('id')->toArray();

        $applications = Application::whereIn('vacancy_id', $vacancyIds)->get();

        return new ApplicationCollection($applications);
    }

    public function show(Application $application): ApplicationResource
    {
        return new ApplicationResource($application);
    }

    public function store(Request $request): ApplicationResource
    {
        $validated = $request->validate([
            'desc' => 'required|string|max:1000',
            'student_id' => 'integer',
            'vacancy_id' => 'required|integer',
            'application_status_id' => 'required|integer',
        ]);

        if ($this->isStudent($request->user())) {
            $student = $request->user()->student;
        } else {
            $student = Student::findOrFail($validated['student_id']);
        }

        $vacancy = Vacancy::findOrFail($validated['vacancy_id']);
        $application_status = ApplicationStatus::findOrFail($validated['application_status_id']);

        $application = Application::create($validated);
        $application->student()->associate($student);
        $application->vacancy()->associate($vacancy);
        $application->application_status()->associate($application_status);
        $application->save();

        return new ApplicationResource($application);
    }

    public function update(Request $request, Application $application): ApplicationResource
    {
        $validated = $request->validate([
            'desc' => 'string|max:1000',
            'student_id' => 'integer',
            'vacancy_id' => 'integer',
            'application_status_id' => 'integer',
        ]);

        $user = $request->user();

        if ($this->isEmployer($user)) {
            $employer = $user->employer;

            if (!$employer || $employer->id != $application->vacancy->employer_id) {
                return response(['message' => 'You do not have permission to do this.'], 403);
            }

            if ($request->has('application_status_id')) {
                $applicationStatus = ApplicationStatus::findOrFail($request->input('application_status_id'));
                $application->application_status()->associate($applicationStatus);
            }
        }

        if ($this->isAdmin($user)) {
            if ($request->has('student_id')) {
                $student = Student::findOrFail($validated['student_id']);
                $application->student()->associate($student);
            }

            if ($request->has('vacancy_id')) {
                $vacancy = Vacancy::findOrFail($validated['vacancy_id']);
                $application->vacancy()->associate($vacancy);
            }

            if ($request->has('application_status_id')) {
                $applicationStatus = ApplicationStatus::findOrFail($request->input('application_status_id'));
                $application->application_status()->associate($applicationStatus);
            }
        }

        $application->update($validated);
        $application->save();

        return new ApplicationResource($application);
    }

    public function destroy(Request $request, Application $application)
    {
        $user = $request->user();

        if ($this->isStudent($user)) {
            if ($application->student_id != $user->student->id) {
                return response()->json(['message' => 'You do not have permission to do this.'], 403);
            }
        }

        if (!$this->isAdmin($user)) {
            return response()->json(['message' => 'You do not have permission to do this.'], 403);
        }

        return $application->delete();
    }
}
