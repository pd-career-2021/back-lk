<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\CoreSkills;

use App\Models\CoreSkill;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class CoreSkillsListLayout extends Table
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
            TD::make('title', "Заголовок")
                ->sort()
                ->cantHide()
                ->filter(Input::make()),

            TD::make('updated_at', __('Last edit'))
                ->sort()
                ->render(function (CoreSkills $core_skills) {
                    return $core_skills->updated_at->toDateTimeString();
                }),

            TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function (CoreSkills $core_skills) {
                    return DropDown::make()
                        ->icon('options-vertical')
                        ->list([

                            Link::make(__('Edit'))
                                ->route('platform.systems.core_skills.edit', $core_skills->id)
                                ->icon('pencil'),

                            Button::make(__('Delete'))
                                ->icon('trash')
                                ->confirm("Данные будут удалены безвозвратно. Вы уверены?")
                                ->method('remove', [
                                    'id' => $core_skills->id,
                                ]),
                        ]);
                }),
        ];
    }
}
