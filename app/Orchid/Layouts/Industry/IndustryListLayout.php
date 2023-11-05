<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Industry;

use App\Models\Industry;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class IndustryListLayout extends Table
{
    /**
     * @var string
     */
    public $target = 'industries';

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

            TD::make('updated_at', __('Last edit'))
                ->sort()
                ->render(function (Industry $industry) {
                    return $industry->updated_at->toDateTimeString();
                }),

            TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function (Industry $industry) {
                    return DropDown::make()
                        ->icon('options-vertical')
                        ->list([
                            Link::make(__('Edit'))
                                ->route('platform.systems.industries.edit', $industry->id)
                                ->icon('pencil'),

                            Button::make(__('Delete'))
                                ->icon('trash')
                                ->confirm("Данные будут удалены безвозвратно. Вы уверены?")
                                ->method('remove', [
                                    'id' => $industry->id,
                                ]),
                        ]);
                }),
        ];
    }
}
