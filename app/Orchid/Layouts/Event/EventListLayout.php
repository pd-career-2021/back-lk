<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Event;

use App\Models\Event;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class EventListLayout extends Table
{
    /**
     * @var string
     */
    public $target = 'events';

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
                ->filter(Input::make())
                ->width('600px'),

            TD::make('date', "Дата")
                ->sort()
                ->render(function (Event $event) {
                    return $event->date->toDateTimeString();
                }),

            TD::make('audience', "Аудитория")
                ->sort()
                ->filter(Input::make())
                ->render(function (Event $event) {
                    return $event->audience->name;
                }),

            TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function (Event $event) {
                    return DropDown::make()
                        ->icon('options-vertical')
                        ->list([

                            Link::make(__('Edit'))
                                ->route('platform.publications.events.edit', $event->id)
                                ->icon('pencil'),

                            Button::make(__('Delete'))
                                ->icon('trash')
                                ->confirm("Данные будут удалены безвозвратно. Вы уверены?")
                                ->method('remove', [
                                    'id' => $event->id,
                                ]),
                        ]);
                }),
        ];
    }
}
