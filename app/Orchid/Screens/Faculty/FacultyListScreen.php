<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Faculty;

use App\Orchid\Layouts\Faculty\FacultyEditLayout;
use App\Orchid\Layouts\Faculty\FacultyListLayout;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Faculty;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class FacultyListScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'faculties' => Faculty::filters()->paginate(10),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Факультеты';
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
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make(__('Add'))
                ->icon('plus')
                ->route('platform.systems.faculties.create'),
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
            FacultyListLayout::class,

            Layout::modal('asyncEditFacultyModal', FacultyEditLayout::class)
                ->async('asyncGetFaculty'),
        ];
    }

    /**
     * @param Faculty $faculty
     *
     * @return array
     */
    public function asyncGetFaculty(Faculty $faculty): iterable
    {
        $faculty->load('attachment');
        return [
            'faculty' => $faculty,
        ];
    }

    /**
     * @param Request $request
     * @param Faculty $faculty
     */
    public function saveFaculty(Request $request, Faculty $faculty): void
    {
        $faculty->fill($request->input('faculty'))->save();
 
        Toast::info("Факультет был сохранен");
    }

    /**
     * @param Request $request
     */
    public function remove(Request $request): void
    {
        Faculty::findOrFail($request->get('id'))->delete();

        Toast::info("Факультет был удален");
    }
}
