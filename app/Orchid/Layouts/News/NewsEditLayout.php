<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\News;

use App\Models\Employer;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Layouts\Rows;

class NewsEditLayout extends Rows
{
    /**
     * Views.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            Input::make('news.title')
                ->type('text')
                ->max(45)
                ->required()
                ->title('Название')
                ->placeholder('Название'),

            TextArea::make('news.preview_text')
                ->rows(3)
                ->max(255)
                ->required()
                ->title('Текст на превью')
                ->placeholder('Краткое описание новости'),

                TextArea::make('news.detail_text')
                ->rows(3)
                ->max(1000)
                ->required()
                ->title('Детальный текст')
                ->placeholder('Подробное описание новости'),

            Relation::make('news.employer_id')
                ->fromModel(Employer::class, 'short_name')
                ->required()
                ->title('Работодатель'),

            Upload::make('event.attachment')
                ->groups('photo')
                ->maxFiles(1)
                ->acceptedFiles('.jpg,.png,.jpeg,.gif,.svg')
                ->title('Превью'),
        ];
    }
}
