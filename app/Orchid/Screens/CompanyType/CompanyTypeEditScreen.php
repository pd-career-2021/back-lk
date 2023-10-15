<?php

declare(strict_types=1);

namespace App\Orchid\Screens\CompanyType;

use App\Models\CompanyType;
use App\Orchid\Layouts\CompanyType\CompanyTypeEditLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class CompanyTypeEditScreen extends Screen
{
    /**
     * @var CompanyType
     */
    public $company_type;

    /**
     * Query data.
     *
     * @param CompanyType $company_type
     *
     * @return array
     */
    public function query(CompanyType $company_type): iterable
    {
        return [
            'company_type' => $company_type,
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->company_type->exists ? 'Редактировать тип компании' : 'Добавить тип компании';
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
                ->canSee($this->company_type->exists),

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

            Layout::block(CompanyTypeEditLayout::class)
                ->title("Информация о типе компании")
                ->description('Основная информация о типе компании')
                ->commands(
                    Button::make(__('Save'))
                        ->type(Color::DEFAULT())
                        ->icon('check')
                        ->canSee($this->company_type->exists)
                        ->method('save')
                ),
        ];
    }
    
    /**
     * @param CompanyType $company_type
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(CompanyType $company_type, Request $request)
    {
        $companyTypeData = $request->get('company_type');

        $$company_type->fill($companyTypeData)->save();

        Toast::info('Тип компании был сохранен');

        return redirect()->route('platform.systems.company_types');
    }

    /**
     * @param CompanyType $company_type
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     */
    public function remove(CompanyType $company_type)
    {
        $company_type->delete();

        Toast::info('Тип компании был удален');

        return redirect()->route('platform.systems.company_types');
    }
}
