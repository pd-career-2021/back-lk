<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Vacancy;

use App\Models\Employer;
use App\Models\Faculty;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Layouts\Rows;

class VacancyEditLayout extends Rows
{
    /**
     * Views.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            Input::make('vacancy.title')
                ->type('text')
                ->max(128)
                ->required()
                ->title('Название')
                ->placeholder('Название'),

            TextArea::make('vacancy.desc')
                ->rows(3)
                ->max(1000)
                ->required()
                ->title('Описание')
                ->placeholder('Описание'),

            Relation::make('vacancy.employer_id')
                ->fromModel(Employer::class, 'full_name')
                ->required()
                ->title('Работодатель'),

            Input::make('vacancy.workplace')
                ->type('text')
                ->max(255)
                ->required()
                ->title('Место работы')
                ->placeholder('Место работы'),

            Input::make('vacancy.map_link')
                ->type('text')
                ->max(1000)
                ->title('Ссылка на Google Maps'),
            
            Relation::make('vacancy.faculty_ids.')
                ->fromModel(Faculty::class, 'title')
                ->multiple()
                ->required()
                ->title('Факультеты')
                ->help("Выберите факультеты для которых будет отображаться вакансия"),
            
            Upload::make('vacancy.attachment')
                ->groups('photo')
                ->maxFiles(1)
                ->acceptedFiles('.jpg,.png,.jpeg,.gif,.svg')
                ->title('Изображение'),
        ];
    }
}
