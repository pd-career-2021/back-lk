<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Student;

use App\Orchid\Layouts\Student\StudentEditLayout;
use App\Orchid\Layouts\Student\StudentListLayout;
use Illuminate\Http\Request;
use App\Models\Student;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class StudentListScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'students' => Student::filters()->paginate(10),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Студенты';
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
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make(__('Add'))
                ->icon('plus')
                ->route('platform.systems.students.create'),
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
            StudentListLayout::class,

            Layout::modal('asyncEditStudentModal', StudentEditLayout::class)
                ->async('asyncGetStudent'),
        ];
    }

    /**
     * @param Student $student
     *
     * @return array
     */
    public function asyncGetStudent(Student $student): iterable
    {
        $student->load('attachment');

        return [
            'student' => $student,
        ];
    }

    /**
     * @param Request $request
     * @param Student $student
     */
    public function saveSudent(Request $request, Student $student): void
    {
        $student->fill($request->input('student'))->save();
 
        Toast::info("Студент был сохранен");
    }

    /**
     * @param Request $request
     */
    public function remove(Request $request): void
    {
        Student::findOrFail($request->get('id'))->delete();

        Toast::info("Студент был удален");
    }
}
