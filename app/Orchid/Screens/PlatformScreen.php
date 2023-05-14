<?php

declare(strict_types=1);

namespace App\Orchid\Screens;

use App\Models\Application;
use App\Models\ApplicationStatus;
use App\Models\Event;
use App\Models\News;
use App\Models\Vacancy;
use App\Orchid\Layouts\Charts\ChartApplications;
use App\Orchid\Layouts\Charts\ChartVacancies;
use App\Orchid\Layouts\Event\EventListLayout;
use App\Orchid\Layouts\News\NewsListLayout;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class PlatformScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'percentageApplications' => Application::countForGroup('application_status_id')->toChart(static function ($id) {
                return ApplicationStatus::find($id)->name;
            }),
            'vacanciesChart' => [
                Vacancy::countByDays(Carbon::now()->subDays(45), null, 'created_at')->toChart("Опубликовано вакансий")
            ],
            'events' => Event::filters()->paginate(10),
            'news' => News::filters()->paginate(10),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'С возвращением, ' . Auth::user()->name;
    }

    /**
     * Display header description.
     *
     * @return string|null
     */
    public function description(): ?string
    {
        return 'Сегодня ' . date('d M') . ', ' . getdate()['weekday'];
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Профиль')
                ->href('./profile')
                ->icon('user'),

            Button::make("Выход")
                ->icon('logout')
                ->method('logout'),
        ];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]
     */
    public function layout(): iterable
    {
        return [
            Layout::columns([
                ChartVacancies::class,
                ChartApplications::class,
            ]),
            Layout::tabs([
                'Мероприятия' => [
                    EventListLayout::class,
                ],
                'Новости'     => [
                    NewsListLayout::class,
                ],
            ]),
        ];
    }

    public function logout()
    {
        Auth::user()->logout();
    }
}
