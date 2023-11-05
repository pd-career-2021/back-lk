<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Industry;

use App\Models\Industry;
use App\Orchid\Layouts\Industry\IndustryEditLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class IndustryEditScreen extends Screen
{
    /**
     * @var Industry
     */
    public $industry;

    /**
     * Query data.
     *
     * @param Industry $industry
     *
     * @return array
     */
    public function query(Industry $industry): iterable
    {
        return [
            'industry' => $industry,
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->industry->exists ? 'Редактировать отрасль' : 'Добавить отрасль';
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
                ->canSee($this->industry->exists),

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

            Layout::block(IndustryEditLayout::class)
                ->title("Информация об отрасли")
                ->description('Основная информация об отрасли')
                ->commands(
                    Button::make(__('Save'))
                        ->type(Color::DEFAULT())
                        ->icon('check')
                        ->canSee($this->industry->exists)
                        ->method('save')
                ),
        ];
    }

    /**
     * @param Industry $industry
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(Industry $industry, Request $request)
    {
        $industryData = $request->get('industry');

        $industry
            ->fill($industryData)
            ->save();

        Toast::info('Отрасль была сохранена');

        return redirect()->route('platform.systems.industries');
    }

    /**
     * @param Industry $industry
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     */
    public function remove(Industry $industry)
    {
        $industry->delete();

        Toast::info('Отрасль была удалена');

        return redirect()->route('platform.systems.industries');
    }
}
