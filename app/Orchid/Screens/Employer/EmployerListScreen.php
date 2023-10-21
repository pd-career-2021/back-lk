<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Employer;

use App\Orchid\Layouts\Employer\EmployerEditLayout;
use App\Orchid\Layouts\Employer\EmployerListLayout;
use Illuminate\Http\Request;
use App\Models\Employer;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class EmployerListScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'employers' => Employer::filters()->paginate(10),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Работодатели';
    }

    /**
     * Display header description.
     *
     * @return string|null
     */
    public function description(): ?string
    {
        return 'Зарегистрированные в системе работодатели';
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
                ->route('platform.systems.employers.create'),
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
            EmployerListLayout::class,

            Layout::modal('asyncEditEmployerModal', EmployerEditLayout::class)
                ->async('asyncGetEmployer'),
        ];
    }

    /**
     * @param Employer $employer
     *
     * @return array
     */
    public function asyncGetEmployer(Employer $employer): iterable
    {
        $employer->load('attachment');
        return [
            'employer' => $employer,
        ];
    }

    /**
     * @param Request $request
     * @param Employer $employer
     */
    public function saveEmployer(Request $request, Employer $employer): void
    {
        $employer->fill($request->input('employer'))->save();
 
        Toast::info("Работодатель был сохранен");
    }

    /**
     * @param Request $request
     */
    public function remove(Request $request): void
    {
        Employer::findOrFail($request->get('id'))->delete();

        Toast::info("Работодатель был удален");
    }
}
