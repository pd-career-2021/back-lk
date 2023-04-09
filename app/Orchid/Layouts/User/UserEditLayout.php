<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\User;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\RadioButtons;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class UserEditLayout extends Rows
{
    /**
     * Views.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            Input::make('user.name')
                ->type('text')
                ->max(45)
                ->required()
                ->title('Имя')
                ->placeholder('Имя'),

            Input::make('user.surname')
            ->type('text')
            ->max(45)
            ->required()
            ->title('Фамилия')
            ->placeholder('Фамилия'),

            Input::make('user.email')
                ->type('email')
                ->required()
                ->title(__('Email'))
                ->placeholder(__('Email')),

            RadioButtons::make('user.sex')
                ->options([
                    'male'   => 'Мужской',
                    'female' => 'Женский',
                ])
                ->required()
                ->title('Пол'),

            // Select::make('user.faculty_id')
            //     ->fromModel(Faculty::class, 'name')
            //     ->multiple()
            //     ->title('Факультет')
            //     ->help('Факультет пользователя'),
        ];
    }
}
