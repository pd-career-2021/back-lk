<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Event;

use App\Models\Event;
use App\Orchid\Layouts\Event\EventEditLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class EventEditScreen extends Screen
{
    /**
     * @var Event
     */
    public $event;

    /**
     * Query data.
     *
     * @param Event $event
     *
     * @return array
     */
    public function query(Event $event): iterable
    {
        $event->load('attachment');
        $event->load('employers');

        $event['employer_ids'] = $event->employers;

        return [
            'event' => $event,
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->event->exists ? 'Редактировать мероприятие' : 'Добавить мероприятие';
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
     * @return Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make(__('Remove'))
                ->icon('trash')
                ->confirm("Данные будут удалены безвозвратно. Вы уверены?")
                ->method('remove')
                ->canSee($this->event->exists),

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

            Layout::block(EventEditLayout::class)
                ->title("Информация о мероприятии")
                ->description('Основная информация о мероприятии')
                ->commands(
                    Button::make(__('Save'))
                        ->type(Color::DEFAULT())
                        ->icon('check')
                        ->canSee($this->event->exists)
                        ->method('save')
                ),
        ];
    }

    /**
     * @param Event $event
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(Event $event, Request $request)
    {
        $eventData = $request->get('event');

        $event
            ->fill($eventData)
            ->save();

        $event->attachment()->sync(
            $request->input('event.attachment', [])
        );

        Toast::info('Мероприятие было сохранено');

        return redirect()->route('platform.publications.events');
    }

    /**
     * @param Event $event
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     */
    public function remove(Event $event)
    {
        $event->delete();

        Toast::info('Мероприятие было удалено');

        return redirect()->route('platform.publications.events');
    }
}
