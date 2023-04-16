<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Vacancy;

use App\Orchid\Layouts\Vacancy\VacancyEditLayout;
use App\Orchid\Layouts\Vacancy\VacancyListLayout;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Vacancy;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class VacancyListScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'vacancies' => Vacancy::filters()->paginate(10),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Вакансии';
    }

    /**
     * Display header description.
     *
     * @return string|null
     */
    public function description(): ?string
    {
        return 'Список опубликованных в системе вакансий';
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
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make(__('Add'))
                ->icon('plus')
                ->route('platform.employment.vacancies.create'),
        ];
    }

    /**
     * Views.
     *
     * @return string[]|\Orchid\Screen\Layout[]
     */
    public function layout(): iterable
    {
        return [
            VacancyListLayout::class,

            Layout::modal('asyncEditVacancyModal', VacancyEditLayout::class)
                ->async('asyncGetVacancy'),
        ];
    }

    /**
     * @param Vacancy $vacancy
     *
     * @return array
     */
    public function asyncGetVacancy(Vacancy $vacancy): iterable
    {
        $vacancy->load('attachment');
        return [
            'vacancy' => $vacancy,
        ];
    }

    /**
     * @param Request $request
     * @param Vacancy $vacancy
     */
    public function saveVacancy(Request $request, Vacancy $vacancy): void
    {
        $vacancy->fill($request->input('vacancy'))->save();
 
        Toast::info("Вакансия была сохранена");
    }

    /**
     * @param Request $request
     */
    public function remove(Request $request): void
    {
        Vacancy::findOrFail($request->get('id'))->delete();

        Toast::info("Вакансия была удалена");
    }
}
