<?php

declare(strict_types=1);

namespace App\Orchid\Screens\ApplicationStatus;

use App\Orchid\Layouts\ApplicationStatus\ApplicationStatusEditLayout;
use App\Orchid\Layouts\ApplicationStatus\ApplicationStatusListLayout;
use Illuminate\Http\Request;
use App\Models\ApplicationStatus;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class ApplicationStatusListScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'application_statuses' => ApplicationStatus::filters()->paginate(10),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Статусы откликов';
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
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make(__('Add'))
                ->icon('plus')
                ->route('platform.systems.application-statuses.create'),
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
            ApplicationStatusListLayout::class,

            Layout::modal('asyncEditApplicationStatusModal', ApplicationStatusEditLayout::class)
                ->async('asyncGetApplicationStatus'),
        ];
    }

    /**
     * @param ApplicationStatus $application_status
     *
     * @return array
     */
    public function asyncGetApplication(ApplicationStatus $application_status): iterable
    {
        return [
            'application_status' => $application_status,
        ];
    }

    /**
     * @param Request $request
     * @param ApplicationStatus $application_status
     */
    public function saveApplication(Request $request, ApplicationStatus $application_status): void
    {
        $application_status->fill($request->input('application_status'))->save();
 
        Toast::info("Статус отклика был сохранен");
    }

    /**
     * @param Request $request
     */
    public function remove(Request $request): void
    {
        ApplicationStatus::findOrFail($request->get('id'))->delete();

        Toast::info("Статус отклика был удален");
    }
}
