<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Vacancy;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Rows;

class VacancyDescLayout extends Rows
{
    /**
     * Views.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            Group::make([
                Select::make('vacancy.salary_type')
                    ->required()
                    ->options([
                        'От' => 'От',
                        'До' => 'До',
                        'По договоренности' => 'По договоренности'
                    ])
                    ->title('Тип зарплаты'),
                Input::make('vacancy.salary')
                    ->type('number')
                    ->title('Зарплата'),
            ])->autoWidth(),

            Select::make('vacancy.employment_type')
            ->required()
            ->options([
                'Проектная работа' => 'Проектная работа',
                'Стажировка' => 'Стажировка',
                'Частичная занятость' => 'Частичная занятость',
                'Полная занятость' => 'Полная занятость'
            ])
            ->title('Тип занятости'),

            Select::make('vacancy.work_experience')
                ->required()
                ->options([
                    'Без опыта' => 'Без опыта',
                    'Не имеет значения' => 'Не имеет значения',
                    'От 1 года до 3 лет' => 'От 1 года до 3 лет',
                    'От 3 до 6 лет' => 'От 3 до 6 лет',
                    'Более 6 лет' => 'Более 6 лет'
                ])
                ->title('Опыт работы'),

            TextArea::make('vacancy.duties')
                ->rows(3)
                ->max(1000)
                ->required()
                ->type('number')
                ->title('Обазянности'),

            TextArea::make('vacancy.conditions')
                ->rows(3)
                ->max(1000)
                ->required()
                ->type('number')
                ->title('Условия'),

            TextArea::make('vacancy.requirements')
                ->rows(3)
                ->max(1000)
                ->required()
                ->type('number')
                ->title('Требования'),

            Input::make('vacancy.core_skills.')
                ->type('text')
                ->required()
                ->title('Ключевые навыки')
                ->help("Перечислите ключевые навыки через запятую"),
        ];
    }
}
