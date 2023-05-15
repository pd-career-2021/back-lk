<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Event;

use App\Models\Audience;
use App\Models\Employer;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Layouts\Rows;

class EventEditLayout extends Rows
{
    /**
     * Views.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            Input::make('event.title')
                ->type('text')
                ->max(45)
                ->required()
                ->title('Название')
                ->placeholder('Название'),

            TextArea::make('event.desc')
                ->rows(3)
                ->max(1000)
                ->required()
                ->title('Описание')
                ->placeholder('Описание'),

            DateTimer::make('event.date')
                ->title('Дата проведения мероприятия')
                ->allowInput()
                ->required()
                ->enableTime()
                ->format24hr(),

            Relation::make('event.audience_id')
                ->fromModel(Audience::class, 'name')
                ->required()
                ->title('Аудитория'),
            
            Relation::make('event.employer_ids.')
                ->fromModel(Employer::class, 'full_name')
                ->multiple()
                ->required()
                ->title('Партнеры')
                ->help("Выберите партнеров данного мероприятия"),

            Upload::make('event.attachment')
                ->groups('photo')
                ->maxFiles(1)
                ->acceptedFiles('.jpg,.png,.jpeg,.gif,.svg')
                ->title('Превью'),
        ];
    }
}
