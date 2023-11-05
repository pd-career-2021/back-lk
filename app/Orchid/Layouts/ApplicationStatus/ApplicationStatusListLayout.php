<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\ApplicationStatus;

use App\Models\ApplicationStatus;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ApplicationStatusListLayout extends Table
{
    /**
     * @var string
     */
    public $target = 'application_statuses';

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::make('id', "id")
                ->sort()
                ->filter(Input::make()),

            TD::make('name', "Название")
                ->sort()
                ->cantHide()
                ->filter(Input::make()),

            TD::make('desc', "Описание")
                ->sort()
                ->cantHide()
                ->filter(Input::make()),

            TD::make('created_at', "Дата создания")
                ->sort()
                ->render(function (ApplicationStatus $application_status) {
                    return $application_status->created_at->toDateTimeString();
                }),

            TD::make('updated_at', __('Last edit'))
                ->sort()
                ->render(function (ApplicationStatus $application_status) {
                    return $application_status->updated_at->toDateTimeString();
                }),

            TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function (ApplicationStatus $application_status) {
                    return DropDown::make()
                        ->icon('options-vertical')
                        ->list([
                            Link::make(__('Edit'))
                                ->route('platform.systems.application-statuses.edit', $application_status->id)
                                ->icon('pencil'),

                            Button::make(__('Delete'))
                                ->icon('trash')
                                ->confirm("Данные будут удалены безвозвратно. Вы уверены?")
                                ->method('remove', [
                                    'id' => $application_status->id,
                                ]),
                        ]);
                }),
        ];
    }
}
