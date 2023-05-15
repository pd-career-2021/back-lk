<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Application;

use App\Models\Application;
use App\Orchid\Layouts\Application\ApplicationEditLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class ApplicationEditScreen extends Screen
{
    /**
     * @var Application
     */
    public $application;

    /**
     * Query data.
     *
     * @param Application $application
     *
     * @return array
     */
    public function query(Application $application): iterable
    {
        $application->load('student');
        $application->load('vacancy');
        $application->load('application_status');

        return [
            'application' => $application,
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->application->exists ? 'Редактировать отклик' : 'Добавить отклик';
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
        return [
            // 'platform.employment.applications',
        ];
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
                ->canSee($this->application->exists),

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
            Layout::block(ApplicationEditLayout::class)
                ->title("Основная информация об отклике")
                ->commands(
                    Button::make(__('Save'))
                        ->type(Color::DEFAULT())
                        ->icon('check')
                        ->canSee($this->application->exists)
                        ->method('save')
                ),
        ];
    }

    /**
     * @param Application $application
     * @param Request $request 
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(Application $application, Request $request)
    {
        $applicationData = $request->get('application');

        $application
            ->fill($applicationData)
            ->save();

        Toast::info('Отклик был сохранен');

        return redirect()->route('platform.employment.applications');

    }

    /**
     * @param Application $application
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     */
    public function remove(Application $application)
    {
        $application->delete();

        Toast::info('Отклик был удален');

        return redirect()->route('platform.employment.applications');
    }
}
