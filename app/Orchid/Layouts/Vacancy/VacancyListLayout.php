<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Vacancy;

use App\Models\Vacancy;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class VacancyListLayout extends Table
{
    /**
     * @var string
     */
    public $target = 'vacancies';

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::make('title', "Название")
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->render(function (Vacancy $vacancy) {
                    return Link::make($vacancy->title)
                        ->route('platform.employment.vacancies.edit', $vacancy);
                }),

            TD::make('desc', "Описание")
                ->sort()
                ->defaultHidden()
                ->filter(Input::make()),

            TD::make('salary_type', 'Тип зарплаты')
                ->sort()
                ->filter(TD::FILTER_SELECT, [
                    'От' => 'От',
                    'До' => 'До',
                    'По договоренности' => 'По договоренности'
                ]),

            TD::make('salary', 'Зарплата')
                ->sort()
                ->filter(Input::make()),

            TD::make('employment_type', 'Тип занятости')
                ->sort()
                ->filter(TD::FILTER_SELECT, [
                    'Проектная работа' => 'Проектная работа',
                    'Стажировка' => 'Стажировка',
                    'Частичная занятость' => 'Частичная занятость',
                    'Полная занятость' => 'Полная занятость'
                ]),

            TD::make('work_experience', 'Опыт работы')
                ->sort()
                ->filter(TD::FILTER_SELECT, [
                    'Без опыта' => 'Без опыта',
                    'Не имеет значения' => 'Не имеет значения',
                    'От 1 года до 3 лет' => 'От 1 года до 3 лет',
                    'От 3 до 6 лет' => 'От 3 до 6 лет',
                    'Более 6 лет' => 'Более 6 лет'
                ]),

            TD::make('duties', "Обязанности")
                ->sort()
                ->defaultHidden()
                ->filter(Input::make()),

            TD::make('conditions', "Требования")
                ->sort()
                ->defaultHidden()
                ->filter(Input::make()),

            TD::make('requirements', "Условия")
                ->sort()
                ->defaultHidden()
                ->filter(Input::make()),

            TD::make('workplace', "Место работы")
                ->sort()
                ->defaultHidden()
                ->filter(Input::make()),

            TD::make('employer', "Работодатель")
                ->sort()
                ->filter(Input::make())
                ->render(function (Vacancy $vacancy) {
                    return $vacancy->employer->short_name;
                }),

            TD::make('updated_at', __('Last edit'))
                ->sort()
                ->render(function (Vacancy $vacancy) {
                    return $vacancy->updated_at->toDateTimeString();
                }),

            TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function (Vacancy $vacancy) {
                    return DropDown::make()
                        ->icon('options-vertical')
                        ->list([

                            Link::make(__('Edit'))
                                ->route('platform.employment.vacancies.edit', $vacancy->id)
                                ->icon('pencil'),

                            Button::make(__('Delete'))
                                ->icon('trash')
                                ->confirm("Данные будут удалены безвозвратно. Вы уверены?")
                                ->method('remove', [
                                    'id' => $vacancy->id,
                                ]),
                        ]);
                }),
        ];
    }
}
