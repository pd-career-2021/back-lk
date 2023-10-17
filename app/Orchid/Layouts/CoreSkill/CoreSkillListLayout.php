<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\CoreSkill;

use App\Models\CoreSkill;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class CoreSkillListLayout extends Table
{
    /**
     * @var string
     */
    public $target = 'core_skills';

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
                ->render(function (CoreSkill $core_skill) {
                    return $core_skill->updated_at->toDateTimeString();
                }),

            TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function (CoreSkill $core_skill) {
                    return DropDown::make()
                        ->icon('options-vertical')
                        ->list([

                            Link::make(__('Edit'))
                                ->route('platform.systems.core_skills.edit', $core_skill->id)
                                ->icon('pencil'),

                            Button::make(__('Delete'))
                                ->icon('trash')
                                ->confirm("Данные будут удалены безвозвратно. Вы уверены?")
                                ->method('remove', [
                                    'id' => $core_skill->id,
                                ]),
                        ]);
                }),
        ];
    }
}
