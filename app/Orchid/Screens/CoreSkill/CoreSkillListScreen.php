<?php

declare(strict_types=1);

namespace App\Orchid\Screens\CoreSkill;

use App\Orchid\Layouts\CoreSkill\CoreSkillEditLayout;
use App\Orchid\Layouts\CoreSkill\CoreSkillListLayout;
use Illuminate\Http\Request;
use App\Models\CoreSkill;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class CoreSkillListScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'core_skills' => CoreSkill::filters()->paginate(10),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Ключевые навыки';
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
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make(__('Add'))
                ->icon('plus')
                ->route('platform.systems.core_skills.create'),
        ];
    }

    /**
     * Views.
     *
     * @return string[]|\Orchid\Screen\Layout[]
     */
    public function layout(): iterable
    {
        return [
            CoreSkillListLayout::class,

            Layout::modal('asyncEditCoreSkillModal', CoreSkillEditLayout::class)
                ->async('asyncGetCoreSkill'),
        ];
    }

    /**
     * @param CoreSkill $core_skill
     *
     * @return array
     */
    public function asyncGetCoreSkill(CoreSkill $core_skill): iterable
    {
        return [
            'core_skill' => $core_skill,
        ];
    }

    /**
     * @param Request $request
     * @param CoreSkill $core_skill
     */
    public function saveCoreSkill(Request $request, CoreSkill $core_skill): void
    {
        $core_skill->fill($request->input('core_skill'))->save();
 
        Toast::info("Ключевой навык был сохранен");
    }

    /**
     * @param Request $request
     */
    public function remove(Request $request): void
    {
        CoreSkill::findOrFail($request->get('id'))->delete();

        Toast::info("Ключевой навык был удален");
    }
}
