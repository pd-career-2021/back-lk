<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Application;

use App\Models\Application;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ApplicationListLayout extends Table
{
    /**
     * @var string
     */
    public $target = 'applications';

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::make('id', "id")
                ->sort()
                ->filter(Input::make()),

            TD::make('application_status', "Статус")
                ->sort()
                ->filter(Input::make())
                ->render(function (Application $application) {
                    return $application->application_status->name;
                }),

            TD::make('vacancy', "Вакансия")
                ->sort()
                ->filter(Input::make())
                ->render(function (Application $application) {
                    return $application->vacancy->title;
                }),

            TD::make('student', "Студент")
                ->sort()
                ->filter(Input::make())
                ->render(function (Application $application) {
                    return $application->student->user->name . ' ' . $application->student->user->surname;
                }),

            TD::make('created_at', "Создано")
                ->sort()
                ->render(function (Application $application) {
                    return $application->created_at->toDateTimeString();
                }),

            TD::make('updated_at', __('Last edit'))
                ->sort()
                ->render(function (Application $application) {
                    return $application->updated_at->toDateTimeString();
                }),

            TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function (Application $application) {
                    return DropDown::make()
                        ->icon('options-vertical')
                        ->list([

                            Link::make(__('Edit'))
                                ->route('platform.employment.applications.edit', $application->id)
                                ->icon('pencil'),

                            Button::make(__('Delete'))
                                ->icon('trash')
                                ->confirm("Данные будут удалены безвозвратно. Вы уверены?")
                                ->method('remove', [
                                    'id' => $application->id,
                                ]),
                        ]);
                }),
        ];
    }
}
