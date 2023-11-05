<?php

namespace App\Http\Controllers;

use App\Filters\Vacancy\VacancyCompanyTypeFilter;
use App\Filters\Vacancy\VacancyCoreSkillsFilter;
use App\Filters\Vacancy\VacancyEmploymentTypeFilter;
use App\Filters\Vacancy\VacancySalaryFilter;
use App\Filters\Vacancy\VacancyWorkExperienceFilter;
use Illuminate\Pipeline\Pipeline;
use App\Http\Library\ApiHelpers;
use App\Http\Resources\VacancyCollection;
use App\Http\Resources\VacancyResource;
use App\Models\CoreSkill;
use App\Models\Employer;
use App\Models\Faculty;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VacancyController extends Controller
{
    use ApiHelpers;

    public function index(): VacancyCollection
    {
        $vacancies = Vacancy::query();
        $response =
            app(Pipeline::class)
            ->send($vacancies)
            ->through([
                VacancyCompanyTypeFilter::class,
                VacancyCoreSkillsFilter::class,
                VacancyEmploymentTypeFilter::class,
                VacancySalaryFilter::class,
                VacancyWorkExperienceFilter::class,
            ])
            ->via('apply')
            ->then(function ($vacancies) {
                return $vacancies->paginate(10);
            });

        return new VacancyCollection($response);
    }

    public function indexEmployerVacancies(Request $request): VacancyCollection
    {
        $user = $request->user();
        $employer = Employer::where('user_id', $user->id)->firstOrFail();
        $vacancies = $employer->vacancies;

        return new VacancyCollection($vacancies);
    }

    public function show(Vacancy $vacancy): VacancyResource
    {
        return new VacancyResource($vacancy);
    }

    public function store(Request $request): VacancyResource
    {
        $validated = $request->validate([
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
        ]);

        $user = $request->user();
        $employer = null;

        if ($this->isEmployer($user)) {
            $employer = Employer::where('user_id', $user->id)->first();
        } elseif ($request->has('employer_id')) {
            $employer = Employer::findOrFail($validated['employer_id']);
        }

        $vacancy = Vacancy::create($validated);
        $vacancy->employer()->associate($employer);

        $validatedFacultyIds = Faculty::whereIn('id', $validated['faculty_ids'])->pluck('id')->toArray();
        $vacancy->faculties()->sync($validatedFacultyIds);

        $validatedCoreSkillIds = [];
        foreach ($validated['core_skills'] as $coreSkillTitle) {
            $coreSkill = CoreSkill::firstOrCreate(['title' => $coreSkillTitle]);
            $validatedCoreSkillIds[] = $coreSkill->id;
        }
        $vacancy->skills()->sync($validatedCoreSkillIds);

        if ($request->hasFile('image')) {
            $vacancy->img_path = $request->file('image')->store('img/v' . $vacancy->id, 'public');
            $vacancy->save();
        }

        return new VacancyResource($vacancy);
    }

    public function update(Request $request, Vacancy $vacancy): VacancyResource
    {
        $validated = $request->validate([
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
        ]);

        $user = $request->user();

        if ($this->isEmployer($user)) {
            $employer = Employer::where('user_id', $user->id)->first();
            if ($vacancy->employer_id !== $employer->id) {
                return response(['message' => 'You do not have permission to do this.'], 403);
            }
        }

        if ($this->isAdmin($user) && $request->has('employer_id')) {
            $employer = Employer::findOrFail($validated['employer_id']);
            $vacancy->employer()->associate($employer);
        }

        $vacancy->update($validated);

        $validatedFacultyIds = Faculty::whereIn('id', $validated['faculty_ids'])->pluck('id')->toArray();
        $vacancy->faculties()->sync($validatedFacultyIds);

        $validatedCoreSkillIds = [];
        foreach ($validated['core_skills'] as $coreSkillTitle) {
            $coreSkill = CoreSkill::firstOrCreate(['title' => $coreSkillTitle]);
            $validatedCoreSkillIds[] = $coreSkill->id;
        }
        $vacancy->skills()->sync($validatedCoreSkillIds);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($vacancy->img_path);
            $vacancy->img_path = $request->file('image')->store('img/v' . $vacancy->employer_id, 'public');
        }

        $vacancy->save();

        return new VacancyResource($vacancy);
    }

    public function destroy(Request $request, Vacancy $vacancy)
    {
        $user = $request->user();

        if ($this->canDeleteVacancy($user, $vacancy)) {
            $vacancy->faculties()->detach();
            $vacancy->skills()->detach();
            return $vacancy->delete();
        }

        return response(['message' => 'You do not have permission to do this.'], 403);
    }

    private function canDeleteVacancy(User $user, Vacancy $vacancy)
    {
        if ($this->isAdmin($user)) {
            return true;
        }

        if ($this->isEmployer($user) && $vacancy->employer_id == $user->employer->id) {
            return true;
        }

        return false;
    }
}
