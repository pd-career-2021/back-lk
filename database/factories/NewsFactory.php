<?php

namespace Database\Factories;

use App\Models\Employer;
use Illuminate\Database\Eloquent\Factories\Factory;

class NewsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => ucfirst($this->faker->words(random_int(4, 7), true)),
            'preview_text' => $this->faker->paragraph(2),
            'detail_text' => $this->faker->text(),
            'employer_id' => Employer::inRandomOrder()->first()->id,
        ];
    }
}
