<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Social;

use App\Models\Social;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class SocialListLayout extends Table
{
    /**
     * @var string
     */
    public $target = 'socials';

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

            TD::make('link', "Ссылка")
                ->sort()
                ->cantHide()
                ->filter(Input::make()),

            TD::make('employer', "Работодатель")
                ->sort()
                ->filter(Input::make())
                ->render(function (Social $social) {
                    return $social->employer->short_name;
                }),

            TD::make('updated_at', __('Last edit'))
                ->sort()
                ->render(function (Social $social) {
                    return $social->updated_at->toDateTimeString();
                }),

            TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function (Social $social) {
                    return DropDown::make()
                        ->icon('options-vertical')
                        ->list([

                            Link::make(__('Edit'))
                                ->route('platform.systems.socials.edit', $social->id)
                                ->icon('pencil'),

                            Button::make(__('Delete'))
                                ->icon('trash')
                                ->confirm("Данные будут удалены безвозвратно. Вы уверены?")
                                ->method('remove', [
                                    'id' => $social->id,
                                ]),
                        ]);
                }),
        ];
    }
}
