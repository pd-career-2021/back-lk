<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Audience;

use App\Models\Audience;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class AudienceListLayout extends Table
{
    /**
     * @var string
     */
    public $target = 'audiences';

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::make('name', "Название")
                ->sort()
                ->cantHide()
                ->filter(Input::make()),

            TD::make('updated_at', __('Last edit'))
                ->sort()
                ->render(function (Audience $audience) {
                    return $audience->updated_at->toDateTimeString();
                }),

            TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function (Audience $audience) {
                    return DropDown::make()
                        ->icon('options-vertical')
                        ->list([

                            Link::make(__('Edit'))
                                ->route('platform.systems.audiences.edit', $audience->id)
                                ->icon('pencil'),

                            Button::make(__('Delete'))
                                ->icon('trash')
                                ->confirm("Данные будут удалены безвозвратно. Вы уверены?")
                                ->method('remove', [
                                    'id' => $audience->id,
                                ]),
                        ]);
                }),
        ];
    }
}
