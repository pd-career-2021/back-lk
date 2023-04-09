<?php

namespace App\Http\Controllers;

use App\Filters\Vacancy\VacancySalaryFilter;
use Illuminate\Pipeline\Pipeline;
use App\Http\Library\ApiHelpers;
use App\Models\Employer;
use App\Models\Faculty;
use App\Models\Vacancy;
use App\Models\CoreSkill;
use Illuminate\Http\Request;
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
        $vacancies = Vacancy::query();
        $response =
            app(Pipeline::class)
            ->send($vacancies)
            ->through([
                VacancySalaryFilter::class
            ])
            ->via('apply')
            ->then(function ($vacancies) {
                return $vacancies->get();
            });

        foreach ($response as $vacancy) {
            $path = ($vacancy->img_path) ? $vacancy->img_path : 'img/blank.jpg';
            $vacancy['image'] = asset('public/storage/' . $path);
            $vacancy['company_name'] = $vacancy->employer->full_name;
        }

        return $response->makeHidden('employer');
    }

    public function indexEmployerVacancies(Request $request)
    {
        $employer_id = Employer::where('user_id', $request->user()->id)->first()->id;
        $vacancies = Vacancy::where('employer_id', $employer_id)->get();

        return $vacancies;
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
            'title' => 'required|string|max:128',
            'desc' => 'required|string|max:1000',
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'salary' => 'integer',
            'salary_type' => 'required|string|in:От,До,По договоренности',
            'employment_type' => 'required|string|in:Проектная работа,Стажировка,Частичная занятость,Полная занятость',
            'work_experience' => 'required|string|in:Без опыта,Не имеет значения,От 1 года до 3 лет,От 3 до 6 лет,Более 6 лет',
            'duties' => 'required|string|max:1000',
            'conditions' => 'required|string|max:1000',
            'requirements' => 'required|string|max:1000',
            'workplace' => 'required|string|max:255',
            'map_link' => 'string|max:1000',
            'employer_id' => 'integer',
            'faculty_ids' => 'required|array',
            'faculty_ids.*' => 'integer',
            'core_skills' => 'required|array',
            'core_skills.*' => 'string|max:64',
            // 'core_skills_ids' => 'required|array',
            // 'core_skills_ids.*' => 'integer',
        ]);
        if ($validator->fails()) {
            return $validator->errors()->all();
        }

        $vacancy = new Vacancy($request->all());
        $user = $request->user();

        if ($this->isEmployer($user)) {
            $employer = Employer::where('user_id', $user->id)->first();
        } else {
            $employer = Employer::find($request->input('employer_id'));
        }

        if (!$employer) {
            return response([
                'message' => 'Employer not found.'
            ], 401);
        } else {
            $vacancy->employer()->associate($employer);
        }
        $vacancy->save();

        $validated = array();
        foreach ($request->input('faculty_ids') as $id) {
            if (Faculty::find($id))
                array_push($validated, $id);
        }
        $vacancy->faculties()->sync($validated);

        $validated = array();
        foreach ($request->input('core_skills') as $coreSkillTitle) {
            $coreSkill = new CoreSkill([
                'title' => $coreSkillTitle,
            ]);
            $coreSkill->save();
            array_push($validated, $coreSkill->id);
        }
        $vacancy->skills()->sync($validated);

        if ($request->hasFile('image')) {
            $employer->img_path = $request->file('image')->store('img/v' . $employer->id, 'public');
        }
        $vacancy->save();
        $path = ($vacancy->img_path) ? $vacancy->img_path : 'img/blank.jpg';
        $vacancy['image'] = asset('public/storage/' . $path);
        $vacancy->faculties;
        $vacancy->skills;

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
        $vacancy['image'] = asset('public/storage/' . $path);
        $vacancy->vacancyType;
        $vacancy->employer->socials;
        $vacancy->faculties;
        $vacancy->skills;

        return $vacancy;
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
            'title' => 'required|string|max:128',
            'desc' => 'required|string|max:1000',
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'salary' => 'integer',
            'salary_type' => 'required|string|in:От,До,По договоренности',
            'employment_type' => 'required|string|in:Проектная работа,Стажировка,Частичная занятость,Полная занятость',
            'work_experience' => 'required|string|in:Без опыта,Не имеет значения,От 1 года до 3 лет,От 3 до 6 лет,Более 6 лет',
            'duties' => 'required|string|max:1000',
            'conditions' => 'required|string|max:1000',
            'requirements' => 'required|string|max:1000',
            'workplace' => 'required|string|max:255',
            'map_link' => 'string|max:1000',
            'employer_id' => 'integer',
            'faculty_ids' => 'required|array',
            'faculty_ids.*' => 'integer',
            'core_skills' => 'required|array',
            'core_skills.*' => 'required|string|max:64',
            // 'core_skills_ids' => 'required|array',
            // 'core_skills_ids.*' => 'integer',
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
                if (!$employer) {
                    return response([
                        'message' => 'Employer not found.'
                    ], 401);
                } else {
                    $vacancy->employer()->associate($employer);
                }
            }
        }

        if ($this->isEmployer($user)) {
            $employer = Employer::where('user_id', $user->id)->first();
        } else {
            $employer = Employer::find($request->input('employer_id'));
        }

        $vacancy->update($request->all());
        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($vacancy->img_path);
            $vacancy->img_path = $request->file('image')->store('img/v' . $employer->id, 'public');
        }

        if ($request->has('faculty_ids')) {
            $validated = array();
            foreach ($request->input('faculty_ids') as $id) {
                if (Faculty::find($id))
                    array_push($validated, $id);
            }
            $vacancy->faculties()->sync($validated);
        }

        if ($request->has('core_skills')) {
            $validated = array();
            foreach ($request->input('core_skills') as $coreSkillTitle) {
                $coreSkill = new CoreSkill([
                    'title' => $coreSkillTitle,
                ]);
                $coreSkill->save();
                array_push($validated, $coreSkill->id);
            }
            $vacancy->skills()->sync($validated);
        }

        $vacancy->save();
        $path = ($vacancy->img_path) ? $vacancy->img_path : 'img/blank.jpg';
        $vacancy['image'] = asset('public/storage/' . $path);
        $vacancy->vacancyType;
        $vacancy->employer;
        $vacancy->faculties;
        $vacancy->skills;

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
            $employer_id = Employer::where('user_id', $user->id)->first()->id;
            if (Vacancy::where('id', $id)->first()->employer_id == $employer_id) {
                $vacancy = Vacancy::find($id);
                $vacancy->faculties()->detach();
                $vacancy->skills()->detach();

                return Vacancy::destroy($id);
            }
        } else if ($this->isAdmin($user)) {
            $vacancy = Vacancy::find($id);
            $vacancy->faculties()->detach();
            $vacancy->skills()->detach();

            return Vacancy::destroy($id);
        }
        return response([
            'message' => 'You do not have permission to do this.'
        ], 401);
    }
}
