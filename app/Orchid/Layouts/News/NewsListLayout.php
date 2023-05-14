<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\News;

use App\Models\News;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class NewsListLayout extends Table
{
    /**
     * @var string
     */
    public $target = 'news';

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

            TD::make('preview_text', "Превью")
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->width('600px'),

            TD::make('employer', "Работодатель")
                ->sort()
                ->filter(Input::make())
                ->render(function (News $news) {
                    return $news->employer->short_name;
                }),

            TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function (News $news) {
                    return DropDown::make()
                        ->icon('options-vertical')
                        ->list([

                            Link::make(__('Edit'))
                                ->route('platform.publications.news.edit', $news->id)
                                ->icon('pencil'),

                            Button::make(__('Delete'))
                                ->icon('trash')
                                ->confirm("Данные будут удалены безвозвратно. Вы уверены?")
                                ->method('remove', [
                                    'id' => $news->id,
                                ]),
                        ]);
                }),
        ];
    }
}
