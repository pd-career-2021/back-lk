<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\CoreSkills;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;

class CoreSkillsEditLayout extends Rows
{
    /**
     * Views.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            Input::make('core_skills.title')
                ->type('text')
                ->max(45)
                ->required()
                ->title('Заголовок')
                ->placeholder('Заголовок'),
        ];
    }
}
