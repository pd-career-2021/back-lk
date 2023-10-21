<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Employer;

use App\Models\CompanyType;
use App\Models\Employer;
use App\Models\User;
use App\Orchid\Layouts\Employer\EmployerEditLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class EmployerEditScreen extends Screen
{
    /**
     * @var Employer
     */
    public $employer;

    /**
     * Query data.
     *
     * @param Employer $employer
     *
     * @return array
     */
    public function query(Employer $employer): iterable
    {
        $employer->load('attachment');
        $employer->load('industries');
        $employer['industry_ids'] = $employer->industries;

        return [
            'employer' => $employer,
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->employer->exists ? 'Редактировать работодателя' : 'Добавить работодателя';
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
                ->canSee($this->employer->exists),

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
            Layout::block(EmployerEditLayout::class)
                ->title("Основная информация")
                ->commands(
                    Button::make(__('Save'))
                        ->type(Color::DEFAULT())
                        ->icon('check')
                        ->canSee($this->employer->exists)
                        ->method('save')
                ),
        ];
    }

    /**
     * @param Employer $employer
     * @param Request $request 
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(Employer $employer, Request $request)
    {
        $employerData = $request->get('employer');
        $employer->fill($employerData);
        $employer->save();

        $employer->user()->associate(User::find($employerData['user_id']));
        $employer->companyType()->associate(CompanyType::find($employerData['company_type_id']));
        $employer->industries()->sync($employerData['industry_ids']);

        $employer->attachment()->sync(
            $request->input('employer.attachment', [])
        );

        $employer->save();

        Toast::info('Работодатель был сохранен');

        return redirect()->route('platform.systems.employers');
    }

    /**
     * @param Employer $employer
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     */
    public function remove(Employer $employer)
    {
        $employer->industries()->detach();
        $employer->delete();

        Toast::info('Работодатель был удален');

        return redirect()->route('platform.systems.employers');
    }
}
