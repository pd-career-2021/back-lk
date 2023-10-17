<?php

declare(strict_types=1);

namespace App\Orchid\Screens\CoreSkills;

use App\Orchid\Layouts\CoreSkills\CoreSkillsEditLayout;
use App\Orchid\Layouts\CoreSkills\CoreSkillsListLayout;
use Illuminate\Http\Request;
use App\Models\CoreSkills;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class CoreSkillsListScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'core_skills' => CoreSkills::filters()->paginate(10),
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
            CoreSkillsListLayout::class,

            Layout::modal('asyncEditCoreSkillsModal', CoreSkillsEditLayout::class)
                ->async('asyncGetCoreSkills'),
        ];
    }

    /**
     * @param CoreSkills $core_skills
     *
     * @return array
     */
    public function asyncGetCoreSkills(CoreSkills $core_skills): iterable
    {
        $core_skills->load('attachment');

        return [
            'core_skills' => $core_skills,
        ];
    }

    /**
     * @param Request $request
     * @param CoreSkills $core_skills
     */
    public function saveCoreSkills(Request $request, CoreSkills $core_skills): void
    {
        $core_skills->fill($request->input('core_skills'))->save();
 
        Toast::info("Ключевой навык был сохранен");
    }

    /**
     * @param Request $request
     */
    public function remove(Request $request): void
    {
        CoreSkills::findOrFail($request->get('id'))->delete();

        Toast::info("Ключевой навык был удален");
    }
}
