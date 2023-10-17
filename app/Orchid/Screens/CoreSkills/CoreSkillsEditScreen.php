<?php

declare(strict_types=1);

namespace App\Orchid\Screens\CoreSkills;

use App\Models\CoreSkills;
use App\Orchid\Layouts\CoreSkills\CoreSkillsEditLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class CoreSkillsEditScreen extends Screen
{
    /**
     * @var CoreSkills
     */
    public $core_skills;

    /**
     * Query data.
     *
     * @param CoreSkills $core_skills
     *
     * @return array
     */
    public function query(CoreSkills $core_skills): iterable
    {
        return [
            'core_skills' => $core_skills,
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->core_skills->exists ? 'Редактировать ключевые навыки' : 'Добавить ключевой навык';
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
                ->canSee($this->core_skills->exists),

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

            Layout::block(CoreSkillsEditLayout::class)
                ->title("Информация о ключевом навыке")
                ->description('Основная информация о ключевом навыке')
                ->commands(
                    Button::make(__('Save'))
                        ->type(Color::DEFAULT())
                        ->icon('check')
                        ->canSee($this->core_skills->exists)
                        ->method('save')
                ),
        ];
    }
    
    /**
     * @param CoreSkills $core_skills
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(CoreSkills $core_skills, Request $request)
    {
        $coreSkillsData = $request->get('core_skills');

        $$core_skills->fill($coreSkillsData)->save();

        Toast::info('Ключевой навык был сохранен');

        return redirect()->route('platform.systems.core_skills');
    }

    /**
     * @param CoreSkills $core_skills
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     */
    public function remove(CoreSkills $core_skills)
    {
        $core_skills->delete();

        Toast::info('Ключевой навык был удален');

        return redirect()->route('platform.systems.core_skills');
    }
}
