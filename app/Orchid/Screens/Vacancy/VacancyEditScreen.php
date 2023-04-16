<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Vacancy;

use App\Models\CoreSkill;
use App\Models\Employer;
use App\Models\Faculty;
use App\Models\Vacancy;
use App\Orchid\Layouts\Vacancy\VacancyDescLayout;
use App\Orchid\Layouts\Vacancy\VacancyEditLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class VacancyEditScreen extends Screen
{
    /**
     * @var Vacancy
     */
    public $vacancy;

    /**
     * Query data.
     *
     * @param Vacancy $vaculty
     *
     * @return array
     */
    public function query(Vacancy $vacancy): iterable
    {
        $vacancy->load('attachment');
        $vacancy->load('skills');
        $vacancy->load('faculties');

        $skills = array();
        foreach ($vacancy->skills as $skill) {
            array_push($skills, $skill->title);
        }
        $vacancy['core_skills'] = implode(", ", $skills);
        $vacancy['faculty_ids'] = $vacancy->faculties;

        return [
            'vacancy' => $vacancy,
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->vacancy->exists ? 'Редактировать вакансию' : 'Добавить вакансию';
    }

    /**
     * Display header description.
     *
     * @return string|null
     */
    public function description(): ?string
    {
        return '';
    }

    /**
     * @return iterable|null
     */
    public function permission(): ?iterable
    {
        return [
            'platform.employment.vacancies',
        ];
    }

    /**
     * Button commands.
     *
     * @return Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make(__('Remove'))
                ->icon('trash')
                ->confirm("Данные будут удалены безвозвратно. Вы уверены?")
                ->method('remove')
                ->canSee($this->vacancy->exists),

            Button::make(__('Save'))
                ->icon('check')
                ->method('save'),
        ];
    }

    /**
     * @return \Orchid\Screen\Layout[]
     */
    public function layout(): iterable
    {
        return [
            Layout::block(VacancyEditLayout::class)
                ->title("Основная информация")
                ->commands(
                    Button::make(__('Save'))
                        ->type(Color::DEFAULT())
                        ->icon('check')
                        ->canSee($this->vacancy->exists)
                        ->method('save')
                ),
            Layout::block(VacancyDescLayout::class)
                ->title("Условия и требования")
                ->commands(
                    Button::make(__('Save'))
                        ->type(Color::DEFAULT())
                        ->icon('check')
                        ->canSee($this->vacancy->exists)
                        ->method('save')
                ),
        ];
    }

    /**
     * @param Vacancy $vacancy
     * @param Request $request 
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(Vacancy $vacancy, Request $request)
    {
        $vacancyData = $request->get('vacancy');
        $vacancyData['core_skills'] = explode(", ", $vacancyData['core_skills'][0]);
        $vacancy->fill($vacancyData);

        $vacancy->employer()->associate(Employer::find($vacancyData['employer_id']));
        $vacancy->save();
        $vacancy->faculties()->sync($vacancyData['faculty_ids']);

        $validated = array();
        foreach ($vacancyData['core_skills'] as $coreSkillTitle) {
            $coreSkill = new CoreSkill(['title' => $coreSkillTitle]);
            $coreSkill->save();
            array_push($validated, $coreSkill->id);
        }
        $vacancy->skills()->sync($validated);

        $vacancy->attachment()->sync(
            $request->input('vacancy.attachment', [])
        );

        Toast::info('Вакансия была сохранена');

        return redirect()->route('platform.employment.vacancies');

    }

    /**
     * @param Vacancy $vacancy
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     */
    public function remove(Vacancy $vacancy)
    {
        $vacancy->faculties()->detach();
        $vacancy->skills()->detach();
        $vacancy->delete();

        Toast::info('Вакансия была удалена');

        return redirect()->route('platform.employment.vacancies');
    }
}
