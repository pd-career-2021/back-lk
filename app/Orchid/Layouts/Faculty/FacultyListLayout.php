<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Faculty;

use App\Models\Faculty;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class FacultyListLayout extends Table
{
    /**
     * @var string
     */
    public $target = 'faculties';

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::make('title', "Название")
                ->sort()
                ->cantHide()
                ->filter(Input::make()),

            TD::make('desc', "Описание")
                ->sort()
                ->cantHide()
                ->filter(Input::make()),

            TD::make('updated_at', __('Last edit'))
                ->sort()
                ->render(function (Faculty $faculty) {
                    return $faculty->updated_at->toDateTimeString();
                }),

            TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function (Faculty $faculty) {
                    return DropDown::make()
                        ->icon('options-vertical')
                        ->list([

                            Link::make(__('Edit'))
                                ->route('platform.systems.faculties.edit', $faculty->id)
                                ->icon('pencil'),

                            Button::make(__('Delete'))
                                ->icon('trash')
                                ->confirm("Данные будут удалены безвозвратно. Вы уверены?")
                                ->method('remove', [
                                    'id' => $faculty->id,
                                ]),
                        ]);
                }),
        ];
    }
}
