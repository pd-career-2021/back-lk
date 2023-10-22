<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Student;

use App\Models\User;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Layouts\Rows;

class StudentEditLayout extends Rows
{
    /**
     * Views.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            Input::make('student.desc')
                ->type('varchar')
                ->max(1000)
                ->required()
                ->title('Описание')
                ->placeholder('Описание'),

            Relation::make('student.user_id')
                ->fromModel(User::class, 'id')
                ->displayAppend('fullName')
                ->required()
                ->title('Пользователь'),
        ];
    }
}
