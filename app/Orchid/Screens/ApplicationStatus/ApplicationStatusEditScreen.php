<?php

declare(strict_types=1);

namespace App\Orchid\Screens\ApplicationStatus;

use App\Models\ApplicationStatus;
use App\Orchid\Layouts\ApplicationStatus\ApplicationStatusEditLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class ApplicationStatusEditScreen extends Screen
{
    /**
     * @var ApplicationStatus
     */
    public $application_status;

    /**
     * Query data.
     *
     * @param ApplicationStatus $application_status
     *
     * @return array
     */
    public function query(ApplicationStatus $application_status): iterable
    {
        return [
            'application_status' => $application_status,
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->application_status->exists ? 'Редактировать статус отклика' : 'Добавить статус отклика';
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
                ->canSee($this->application_status->exists),

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
            Layout::block(ApplicationStatusEditLayout::class)
                ->title("Основная информация о статусе отклика")
                ->commands(
                    Button::make(__('Save'))
                        ->type(Color::DEFAULT())
                        ->icon('check')
                        ->canSee($this->application_status->exists)
                        ->method('save')
                ),
        ];
    }

    /**
     * @param ApplicationStatus $application_status
     * @param Request $request 
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(ApplicationStatus $application_status, Request $request)
    {
        $applicationStatusData = $request->get('application_status');

        $application_status
            ->fill($applicationStatusData)
            ->save();

        Toast::info('Статус отклика был сохранен');

        return redirect()->route('platform.systems.application-statuses');

    }

    /**
     * @param ApplicationStatus $application_status
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     */
    public function remove(ApplicationStatus $application_status)
    {
        $application_status->delete();

        Toast::info('Статус отклика был удален');

        return redirect()->route('platform.systems.application-statuses');
    }
}
