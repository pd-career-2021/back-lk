<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Application;

use App\Models\ApplicationStatus;
use App\Models\User;
use App\Models\Vacancy;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Rows;

class ApplicationEditLayout extends Rows
{
    /**
     * Views.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            TextArea::make('application.desc')
                ->rows(3)
                ->max(1000)
                ->required()
                ->title('Описание')
                ->placeholder('Описание'),

            Relation::make('application.student_id')
                ->fromModel(User::class, 'id')
                ->displayAppend('fullName')
                ->required()
                ->title('Студент'),

            Relation::make('application.application_status_id')
                ->fromModel(ApplicationStatus::class, 'name')
                ->required()
                ->title('Статус'),

            Relation::make('application.vacancy_id')
                ->fromModel(Vacancy::class, 'title')
                ->required()
                ->title('Вакансия'),
        ];
    }
}
