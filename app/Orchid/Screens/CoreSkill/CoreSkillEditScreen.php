<?php

declare(strict_types=1);

namespace App\Orchid\Screens\CoreSkill;

use App\Models\CoreSkill;
use App\Orchid\Layouts\CoreSkill\CoreSkillEditLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class CoreSkillEditScreen extends Screen
{
    /**
     * @var CoreSkill
     */
    public $core_skill;

    /**
     * Query data.
     *
     * @param CoreSkill $core_skill
     *
     * @return array
     */
    public function query(CoreSkill $core_skill): iterable
    {
        return [
            'core_skill' => $core_skill,
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->core_skill->exists ? 'Редактировать ключевые навыки' : 'Добавить ключевой навык';
    }

    /**
     * Display header description.
     *
     * @return string|null
     */
    public function description(): ?string
    {
        return '';
    }

    /**
     * @return iterable|null
     */
    public function permission(): ?iterable
    {
        return [];
    }

    /**
     * Button commands.
     *
     * @return Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make(__('Remove'))
                ->icon('trash')
                ->confirm("Данные будут удалены безвозвратно. Вы уверены?")
                ->method('remove')
                ->canSee($this->core_skill->exists),

            Button::make(__('Save'))
                ->icon('check')
                ->method('save'),
        ];
    }

    /**
     * @return \Orchid\Screen\Layout[]
     */
    public function layout(): iterable
    {
        return [

            Layout::block(CoreSkillEditLayout::class)
                ->title("Информация о ключевом навыке")
                ->description('Основная информация о ключевом навыке')
                ->commands(
                    Button::make(__('Save'))
                        ->type(Color::DEFAULT())
                        ->icon('check')
                        ->canSee($this->core_skill->exists)
                        ->method('save')
                ),
        ];
    }
    
    /**
     * @param CoreSkill $core_skill
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(CoreSkill $core_skill, Request $request)
    {
        $coreSkillData = $request->get('core_skill');

        $core_skill->fill($coreSkillData)->save();

        Toast::info('Ключевой навык был сохранен');

        return redirect()->route('platform.systems.core_skills');
    }

    /**
     * @param CoreSkill $core_skill
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     */
    public function remove(CoreSkill $core_skill)
    {
        $core_skill->delete();

        Toast::info('Ключевой навык был удален');

        return redirect()->route('platform.systems.core_skills');
    }
}
