<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\CompanyType;

use App\Models\CompanyType;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class CompanyTypeListLayout extends Table
{
    /**
     * @var string
     */
    public $target = 'company_types';

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::make('title', "Заголовок")
                ->sort()
                ->cantHide()
                ->filter(Input::make()),

            TD::make('updated_at', __('Last edit'))
                ->sort()
                ->render(function (CompanyType $company_type) {
                    return $company_type->updated_at->toDateTimeString();
                }),

            TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function (CompanyType $company_type) {
                    return DropDown::make()
                        ->icon('options-vertical')
                        ->list([

                            Link::make(__('Edit'))
                                ->route('platform.systems.company_type.edit', $company_type->id)
                                ->icon('pencil'),

                            Button::make(__('Delete'))
                                ->icon('trash')
                                ->confirm("Данные будут удалены безвозвратно. Вы уверены?")
                                ->method('remove', [
                                    'id' => $company_type->id,
                                ]),
                        ]);
                }),
        ];
    }
}
