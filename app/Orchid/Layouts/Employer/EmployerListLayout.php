<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Employer;

use App\Models\Employer;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class EmployerListLayout extends Table
{
    /**
     * @var string
     */
    public $target = 'employers';

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::make('full_name', "Полное наименование")
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->render(function (Employer $employer) {
                    return Link::make($employer->full_name)
                        ->route('platform.systems.employers.edit', $employer);
                }),

            TD::make('short_name', "Краткое наименование")
                ->sort()
                ->filter(Input::make()),

            TD::make('desc', "Описание")
                ->sort()
                ->defaultHidden()
                ->filter(Input::make()),

            TD::make('user', "Представитель")
                ->sort()
                ->filter(Input::make())
                ->render(function (Employer $employer) {
                    return $employer->user->name . ' '. $employer->user->surname;
                }),

            TD::make('company_type', "Тип компании")
                ->sort()
                ->filter(Input::make())
                ->render(function (Employer $employer) {
                    return $employer->companyType->title;
                }),

            TD::make('created_at', "Дата регистрации")
                ->sort()
                ->render(function (Employer $employer) {
                    return $employer->created_at->toDateTimeString();
                }),

            TD::make('updated_at', __('Last edit'))
                ->sort()
                ->render(function (Employer $employer) {
                    return $employer->updated_at->toDateTimeString();
                }),

            TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function (Employer $employer) {
                    return DropDown::make()
                        ->icon('options-vertical')
                        ->list([

                            Link::make(__('Edit'))
                                ->route('platform.systems.employers.edit', $employer->id)
                                ->icon('pencil'),

                            Button::make(__('Delete'))
                                ->icon('trash')
                                ->confirm("Данные будут удалены безвозвратно. Вы уверены?")
                                ->method('remove', [
                                    'id' => $employer->id,
                                ]),
                        ]);
                }),
        ];
    }
}
