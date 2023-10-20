<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Audience;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;

class AudienceEditLayout extends Rows
{
    /**
     * Views.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            Input::make('audience.name')
                ->type('text')
                ->max(45)
                ->required()
                ->title('Название')
                ->placeholder('Название'),
        ];
    }
}
