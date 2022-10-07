<?php

namespace Database\Factories;

use App\Models\ApplicationStatus;
use App\Models\Student;
use App\Models\Vacancy;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApplicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'desc' => $this->faker->text(),
            'student_id' => Student::inRandomOrder()->first()->id,
            'vacancy_id' => Vacancy::inRandomOrder()->first()->id,
            'application_status_id' => ApplicationStatus::inRandomOrder()->first()->id,
        ];
    }
}
