<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Application;

use App\Orchid\Layouts\Application\ApplicationEditLayout;
use App\Orchid\Layouts\Application\ApplicationListLayout;
use Illuminate\Http\Request;
use App\Models\Application;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class ApplicationListScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'applications' => Application::filters()->paginate(10),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Отклики';
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
                ->route('platform.employment.applications.create'),
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
            ApplicationListLayout::class,

            Layout::modal('asyncEditApplicationModal', ApplicationEditLayout::class)
                ->async('asyncGetApplication'),
        ];
    }

    /**
     * @param Application $application
     *
     * @return array
     */
    public function asyncGetApplication(Application $application): iterable
    {
        return [
            'application' => $application,
        ];
    }

    /**
     * @param Request $request
     * @param Application $application
     */
    public function saveApplication(Request $request, Application $application): void
    {
        $application->fill($request->input('application'))->save();
 
        Toast::info("Отклик был сохранен");
    }

    /**
     * @param Request $request
     */
    public function remove(Request $request): void
    {
        Application::findOrFail($request->get('id'))->delete();

        Toast::info("Отклик был удален");
    }
}
