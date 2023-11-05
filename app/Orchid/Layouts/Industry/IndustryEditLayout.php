<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Industry;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;

class IndustryEditLayout extends Rows
{
    /**
     * Views.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            Input::make('industry.title')
                ->type('text')
                ->max(64)
                ->required()
                ->title('Название')
                ->placeholder('Название'),
        ];
    }
}
