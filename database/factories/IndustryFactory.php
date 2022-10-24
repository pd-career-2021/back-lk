<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class IndustryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => ucfirst($this->faker->words(2, true)),
        ];
    }
}
