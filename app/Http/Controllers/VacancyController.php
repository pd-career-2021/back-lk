<?php

namespace App\Http\Controllers;

use App\Http\Library\ApiHelpers;
use Illuminate\Http\Request;
use App\Models\Vacancy;
use App\Models\Employer;
use App\Models\Faculty;
use App\Models\VacancyType;
use App\Models\VacancyFunction;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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
        $vacancies = Vacancy::all();
        foreach($vacancies as $vacancy) {
            $path = ($vacancy->img_path) ? $vacancy->img_path : 'img/blank.jpg';
            $vacancy['image'] = asset('storage/'.$path);
            $vacancy['vacancy_type'] = $vacancy->vacancyType;
            $vacancy['employer'] = $vacancy->employer;
            $vacancy['faculties'] = $vacancy->faculties;
            $vacancy['functions'] = $vacancy->functions;
        }
        
        return response()->json($vacancies);
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
            'title' => 'required|string|max:64|unique:vacancies,title',
            'desc' => 'required|string|max:1000',
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'link' => 'required|string|max:255',
            'salary' => 'required|numeric|max:13',
            'workplace' => 'required|string|max:255',
            'level' => 'required|string|max:64',
            'vacancy_type_id' => 'required|integer',
            'employer_id' => 'integer',
            'faculty_ids' => 'required|array',
            'faculty_ids.*' => 'integer',
            'function_ids' => 'required|array',
            'function_ids.*' => 'integer',
        ]);
        if ($validator->fails()) {
            return $validator->errors()->all();
        }
        
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
        
        $vacancy_type = VacancyType::find($request->input('vacancy_type_id'));
        if(!$vacancy_type) {
            return response([
                'message' => 'Vacancy type not found.'
            ], 401);
        } else {
            $vacancy->vacancyType()->associate($vacancy_type);
        }
        $vacancy->save();
        
        $validated = array();
        foreach ($request->input('faculty_ids') as $id) {
            if (Faculty::find($id))
                array_push($validated, $id);
        }
        $vacancy->faculties()->sync($validated);
        
        $validated = array();
        foreach ($request->input('function_ids') as $id) {
            if (VacancyFunction::find($id))
                array_push($validated, $id);
        }
        $vacancy->functions()->sync($validated);
        
        if($request->hasFile('image')) {
            $employer->img_path = $request->file('image')->store('img/e'.$employer->id, 'public');
        }
        $vacancy->save();
        $path = ($vacancy->img_path) ? $vacancy->img_path : 'img/blank.jpg';
        $vacancy['image'] = asset('storage/'.$path);
        $vacancy['vacancy_type'] = $vacancy->vacancyType()->get();
        $vacancy['employer'] = $vacancy->employer()->get();
        $vacancy['faculties'] = $vacancy->faculties()->get();
        $vacancy['functions'] = $vacancy->functions()->get();
        
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
        $vacancy = Vacancy::find($id);
        $path = ($vacancy->img_path) ? $vacancy->img_path : 'img/blank.jpg';
        $vacancy['image'] = asset('storage/'.$path);
        $vacancy['vacancy_type'] = $vacancy->vacancyType;
        $vacancy['employer'] = $vacancy->employer;
        $vacancy['faculties'] = $vacancy->faculties;
        $vacancy['functions'] = $vacancy->functions;
        
        return response()->json($vacancy);
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
            'title' => 'string|max:64|unique:vacancies,title',
            'desc' => 'string|max:1000',
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'link' => 'string|max:255',
            'salary' => 'numeric|max:13',
            'workplace' => 'string|max:255',
            'level' => 'string|max:64',
            'vacancy_type_id' => 'integer',
            'employer_id' => 'integer',
            'faculty_ids' => 'array',
            'faculty_ids.*' => 'integer',
            'function_ids' => 'array',
            'function_ids.*' => 'integer',
        ]);
        if ($validator->fails()) {
            return $validator->errors()->all();
        }
        
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
        
        if ($this->isEmployer($user)) {
            $employer = Employer::find($user->id);
        } else {
            $employer = Employer::find($request->input('employer_id'));
        }
        
        $vacancy->update($request->all());
        if($request->hasFile('image')) {
            Storage::disk('public')->delete($vacancy->img_path);
            $vacancy->img_path = $request->file('image')->store('img/e'.$employer->id, 'public');
        }
        
        if ($request->has('stage_id')) {
            $vacancy_type = VacancyType::find($request->input('vacancy_type_id'));
            if(!$vacancy_type) {
                return response([
                    'message' => 'Vacancy type not found.'
                ], 401);
            } else {
                $vacancy->vacancyType()->associate($vacancy_type);
            }
        }
        
        if ($request->has('faculty_ids')) {
            $validated = array();
            foreach ($request->input('faculty_ids') as $id) {
                if (Faculty::find($id))
                    array_push($validated, $id);
            }
            $vacancy->faculties()->sync($validated);
        }
        
        if ($request->has('function_ids')) {
            $validated = array();
            foreach ($request->input('function_ids') as $id) {
                if (VacancyFunction::find($id))
                    array_push($validated, $id);
            }
            $vacancy->functions()->sync($validated);
        }
        
        $vacancy->save();
        $path = ($vacancy->img_path) ? $vacancy->img_path : 'img/blank.jpg';
        $vacancy['image'] = asset('storage/'.$path);
        $vacancy['vacancy_type'] = $vacancy->vacancyType;
        $vacancy['employer'] = $vacancy->employer;
        $vacancy['faculties'] = $vacancy->faculties;
        $vacancy['functions'] = $vacancy->functions;
        
        return $vacancy;
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        if ($this->isEmployer($user)) {
            $employer_id = Employer::where('user_id', $request->user()->id)->first()->id;
            if (Vacancy::where('id', $id)->first()->employer_id == $employer_id) {
                $vacancy = Vacancy::find($id);
                $vacancy->faculties()->detach();
                $vacancy->functions()->detach();
                return Vacancy::destroy($id);
            } else {
                return response([
                    'message' => 'You do not have permission to do this.'
                ], 401);
            }
        } else if ($this->isAdmin($user)) {
            $vacancy = Vacancy::find($id);
            $vacancy->faculties()->detach();
            $vacancy->functions()->detach();
            return Vacancy::destroy($id);
        }
    }
}