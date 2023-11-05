<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Industry;

use App\Orchid\Layouts\Industry\IndustryEditLayout;
use App\Orchid\Layouts\Industry\IndustryListLayout;
use Illuminate\Http\Request;
use App\Models\Industry;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class IndustryListScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'industries' => Industry::filters()->paginate(10),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Отрасли';
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
                ->route('platform.systems.industries.create'),
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
            IndustryListLayout::class,

            Layout::modal('asyncEditIndustryModal', IndustryEditLayout::class)
                ->async('asyncGetIndustry'),
        ];
    }

    /**
     * @param Industry $industry
     *
     * @return array
     */
    public function asyncGetIndustry(Industry $industry): iterable
    {
        return [
            'industry' => $industry,
        ];
    }

    /**
     * @param Request $request
     * @param Industry $industry
     */
    public function saveIndustry(Request $request, Industry $industry): void
    {
        $industry->fill($request->input('industry'))->save();
 
        Toast::info("Отрасль была сохранена");
    }

    /**
     * @param Request $request
     */
    public function remove(Request $request): void
    {
        Industry::findOrFail($request->get('id'))->delete();

        Toast::info("Отрасль была удалена");
    }
}
