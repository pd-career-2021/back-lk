<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Event;

use App\Orchid\Layouts\Event\EventEditLayout;
use App\Orchid\Layouts\Event\EventListLayout;
use Illuminate\Http\Request;
use App\Models\Event;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class EventListScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'events' => Event::filters()->paginate(10),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Мероприятия';
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
            // 'platform.systems.events',
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
                ->route('platform.publications.events.create'),
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
            EventListLayout::class,

            Layout::modal('asyncEditEventModal', EventEditLayout::class)
                ->async('asyncGetEvent'),
        ];
    }

    /**
     * @param Event $event
     *
     * @return array
     */
    public function asyncGetEvent(Event $event): iterable
    {
        $event->load('attachment');
        return [
            'event' => $event,
        ];
    }

    /**
     * @param Request $request
     * @param Event $event
     */
    public function saveEvent(Request $request, Event $event): void
    {
        $event->fill($request->input('event'))->save();
 
        Toast::info("Мероприятие было сохранено");
    }

    /**
     * @param Request $request
     */
    public function remove(Request $request): void
    {
        Event::findOrFail($request->get('id'))->delete();

        Toast::info("Мероприятие было удалено");
    }
}
