<?php

namespace Database\Factories;

use App\Models\Employer;
use Illuminate\Database\Eloquent\Factories\Factory;

class VacancyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $salary_type = $this->faker->randomElement(['От', 'До', 'По договоренности']);
        return [
            'title' => ucfirst($this->faker->words(random_int(3, 5), true)),
            'desc' => $this->faker->text(),
            'salary' => $salary_type == 'По договоренности' ? 0 : $this->ceilCoefficient($this->faker->numberBetween(10000, 250000)),
            'salary_type' => $salary_type,
            'employment_type' => $this->faker->randomElement([
                'Проектная работа',
                'Стажировка',
                'Частичная занятость',
                'Полная занятость'
            ]),
            'work_experience' => $this->faker->randomElement([
                'Без опыта',
                'Не имеет значения',
                'От 1 года до 3 лет',
                'От 3 до 6 лет',
                'Более 6 лет'
            ]),
            'duties' => implode("\n", $this->faker->sentences(3)),
            'conditions' => implode("\n", $this->faker->sentences(3)),
            'requirements' => implode("\n", $this->faker->sentences(3)),
            'workplace' => $this->faker->address(),
            'map_link' => 'https://goo.gl/maps/agvMe9vLuvg6TKDR6',
            'employer_id' => Employer::inRandomOrder()->first()->id,
        ];
    }

    private function ceilCoefficient($number, $rate = 1000)
    {
        return ceil(ceil($number) / $rate) * $rate;
    }
}
