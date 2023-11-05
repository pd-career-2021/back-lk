<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\ApplicationStatus;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Rows;

class ApplicationStatusEditLayout extends Rows
{
    /**
     * Views.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            Input::make('application_status.name')
                ->required()
                ->max(64)
                ->title('Название'),

            TextArea::make('application_status.desc')
                ->rows(3)
                ->max(1000)
                ->required()
                ->title('Описание')
                ->placeholder('Описание'),
        ];
    }
}
