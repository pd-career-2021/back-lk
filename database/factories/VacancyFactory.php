<?php

namespace Database\Factories;

use App\Models\Employer;
use App\Models\VacancyType;
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
        return [
            'title' => ucfirst($this->faker->words(random_int(3, 5), true)),
            'desc' => $this->faker->text(),
            'short_desc' => $this->faker->text(100),
            'skills' => $this->faker->paragraph(),
            'link' => $this->faker->url(),
            'map' => 'https://goo.gl/maps/agvMe9vLuvg6TKDR6',
            'salary' => $this->faker->randomFloat(2, 10000, 250000),
            'workplace' => $this->faker->address(),
            'level' => ucfirst($this->faker->word()),
            'vacancy_type_id' => VacancyType::inRandomOrder()->first()->id,
            'employer_id' => Employer::inRandomOrder()->first()->id,
        ];
    }
}
