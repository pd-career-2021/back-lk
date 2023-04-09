<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Faculty;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Picture;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Layouts\Rows;

class FacultyEditLayout extends Rows
{
    /**
     * Views.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            Input::make('faculty.title')
                ->type('text')
                ->max(128)
                ->required()
                ->title('Название')
                ->placeholder('Название'),

            TextArea::make('faculty.desc')
                ->rows(3)
                ->max(1000)
                ->required()
                ->title('Описание')
                ->placeholder('Описание'),

            Upload::make('faculty.attachment')
                ->groups('photo')
                ->maxFiles(1)
                ->acceptedFiles('.jpg,.png,.jpeg,.gif,.svg')
        ];
    }
}
