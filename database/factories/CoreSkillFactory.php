<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CoreSkillFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => ucfirst($this->faker->words(random_int(2, 3), true)),
        ];
    }
}
