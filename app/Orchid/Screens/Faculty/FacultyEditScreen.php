<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Faculty;

use App\Models\Faculty;
use App\Orchid\Layouts\Faculty\FacultyEditLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class FacultyEditScreen extends Screen
{
    /**
     * @var Faculty
     */
    public $faculty;

    /**
     * Query data.
     *
     * @param Faculty $faculty
     *
     * @return array
     */
    public function query(Faculty $faculty): iterable
    {
        $faculty->load('attachment');

        return [
            'faculty' => $faculty,
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->faculty->exists ? 'Редактировать факультет' : 'Добавить факультет';
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
            'platform.systems.faculties',
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
                ->canSee($this->faculty->exists),

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

            Layout::block(FacultyEditLayout::class)
                ->title("Информация о факультете")
                ->description('Основная информация о факультете')
                ->commands(
                    Button::make(__('Save'))
                        ->type(Color::DEFAULT())
                        ->icon('check')
                        ->canSee($this->faculty->exists)
                        ->method('save')
                ),
        ];
    }

    /**
     * @param Faculty $faculty
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(Faculty $faculty, Request $request)
    {
        $facultyData = $request->get('faculty');

        $faculty
            ->fill($facultyData)
            ->save();

        $faculty->attachment()->sync(
            $request->input('faculty.attachment', [])
        );

        Toast::info('Факультет был сохранен');

        return redirect()->route('platform.systems.faculties');
    }

    /**
     * @param Faculty $faculty
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     */
    public function remove(Faculty $faculty)
    {
        $faculty->delete();

        Toast::info('Факультет был удален');

        return redirect()->route('platform.systems.faculties');
    }
}
