<?php

declare(strict_types=1);

namespace App\Orchid\Screens\CompanyType;

use App\Orchid\Layouts\CompanyType\CompanyTypeEditLayout;
use App\Orchid\Layouts\CompanyType\CompanyTypeListLayout;
use Illuminate\Http\Request;
use App\Models\CompanyType;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class CompanyTypeListScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'company_types' => CompanyType::filters()->paginate(10),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Типы компаний';
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
                ->route('platform.systems.company_types.create'),
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
            CompanyTypeListLayout::class,

            Layout::modal('asyncEditCompanyTypeModal', CompanyTypeEditLayout::class)
                ->async('asyncGetCompanyType'),
        ];
    }

    /**
     * @param CompanyType $company_type
     *
     * @return array
     */
    public function asyncGetCompanyType(CompanyType $company_type): iterable
    {
        return [
            'company_type' => $company_type,
        ];
    }

    /**
     * @param Request $request
     * @param CompanyType $company_type
     */
    public function saveCompanyType(Request $request, CompanyType $company_type): void
    {
        $company_type->fill($request->input('company_type'))->save();
 
        Toast::info("Тип компании был сохранен");
    }

    /**
     * @param Request $request
     */
    public function remove(Request $request): void
    {
        CompanyType::findOrFail($request->get('id'))->delete();

        Toast::info("Тип компании был удален");
    }
}
