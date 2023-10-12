<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Social;

use App\Models\Social;
use App\Orchid\Layouts\Social\SocialEditLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class SocialEditScreen extends Screen
{
    /**
     * @var Social
     */
    public $social;

    /**
     * Query data.
     *
     * @param Social $social
     *
     * @return array
     */
    public function query(Social $social): iterable
    {
        return [
            'social' => $social,
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->social->exists ? 'Редактировать социальную сеть' : 'Добавить социальную сеть';
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
                ->canSee($this->social->exists),

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

            Layout::block(SocialEditLayout::class)
                ->title("Информация о социальной сети")
                ->description('Основная информация о социальной сети')
                ->commands(
                    Button::make(__('Save'))
                        ->type(Color::DEFAULT())
                        ->icon('check')
                        ->canSee($this->social->exists)
                        ->method('save')
                ),
        ];
    }
    
    /**
     * @param Social $social
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(Social $social, Request $request)
    {
        $socialData = $request->get('social');

        $social->fill($socialData)->save();

        Toast::info('Социальная сеть была сохранена');

        return redirect()->route('platform.systems.socials');
    }

    /**
     * @param Social $social
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     */
    public function remove(Social $social)
    {
        $social->delete();

        Toast::info('Социальная сеть была удалена');

        return redirect()->route('platform.systems.socials');
    }
}
