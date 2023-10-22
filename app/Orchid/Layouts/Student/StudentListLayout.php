<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Student;

use App\Models\Student;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class StudentListLayout extends Table
{
    /**
     * @var string
     */
    public $target = 'students';

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::make('desc', "Описание")
                ->sort()
                ->cantHide()
                ->filter(Input::make()),

            TD::make('user', "Пользователь")
                ->sort()
                ->filter(Input::make())
                ->render(function (Student $student) {
                    return $student->user->name . ' ' . $student->user->surname;
                }),

            TD::make('updated_at', __('Last edit'))
                ->sort()
                ->render(function (Student $student) {
                    return $student->updated_at->toDateTimeString();
                }),

            TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function (Student $student) {
                    return DropDown::make()
                        ->icon('options-vertical')
                        ->list([

                            Link::make(__('Edit'))
                                ->route('platform.systems.students.edit', $student->id)
                                ->icon('pencil'),

                            Button::make(__('Delete'))
                                ->icon('trash')
                                ->confirm("Данные будут удалены безвозвратно. Вы уверены?")
                                ->method('remove', [
                                    'id' => $student->id,
                                ]),
                        ]);
                }),
        ];
    }
}
