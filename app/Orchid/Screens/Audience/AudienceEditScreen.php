<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Audience;

use App\Models\Audience;
use App\Orchid\Layouts\Audience\AudienceEditLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class AudienceEditScreen extends Screen
{
    /**
     * @var Audience
     */
    public $audience;

    /**
     * Query data.
     *
     * @param Audience $audience
     *
     * @return array
     */
    public function query(Audience $audience): iterable
    {
        return [
            'audience' => $audience,
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->audience->exists ? 'Редактировать аудиторию' : 'Добавить аудиторию';
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
                ->canSee($this->audience->exists),

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

            Layout::block(AudienceEditLayout::class)
                ->title("Информация о социальной сети")
                ->description('Основная информация о социальной сети')
                ->commands(
                    Button::make(__('Save'))
                        ->type(Color::DEFAULT())
                        ->icon('check')
                        ->canSee($this->audience->exists)
                        ->method('save')
                ),
        ];
    }
    
    /**
     * @param Audience $audience
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(Audience $audience, Request $request)
    {
        $audienceData = $request->get('audience');

        $audience->fill($audienceData)->save();

        Toast::info('Аудитория была сохранена');

        return redirect()->route('platform.systems.audiences');
    }

    /**
     * @param Audience $audience
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     */
    public function remove(Audience $audience)
    {
        $audience->delete();

        Toast::info('Аудитория была удалена');

        return redirect()->route('platform.systems.audiences');
    }
}
