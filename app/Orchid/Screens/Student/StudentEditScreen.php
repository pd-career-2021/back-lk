<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Student;

use App\Models\Student;
use App\Orchid\Layouts\Student\StudentEditLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class StudentEditScreen extends Screen
{
    /**
     * @var Student
     */
    public $student;

    /**
     * Query data.
     *
     * @param Student $student
     *
     * @return array
     */
    public function query(Student $student): iterable
    {
        return [
            'student' => $student,
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->student->exists ? 'Редактировать студента' : 'Добавить студента';
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
                ->canSee($this->student->exists),

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

            Layout::block(StudentEditLayout::class)
                ->title("Информация о студенте")
                ->description('Основная информация о студенте')
                ->commands(
                    Button::make(__('Save'))
                        ->type(Color::DEFAULT())
                        ->icon('check')
                        ->canSee($this->student->exists)
                        ->method('save')
                ),
        ];
    }
    
    /**
     * @param Student $student
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(Student $student, Request $request)
    {
        $studentData = $request->get('student');

        $student->fill($studentData)->save();

        Toast::info('Студент был сохранен');

        return redirect()->route('platform.systems.students');
    }

    /**
     * @param Student $student
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     */
    public function remove(Student $student)
    {
        $student->delete();

        Toast::info('Студент был удален');

        return redirect()->route('platform.systems.students');
    }
}
